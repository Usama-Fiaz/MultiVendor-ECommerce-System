import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:geocoding/geocoding.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:sixvalley_vendor_app/data/model/response/base/api_response.dart';
import 'package:sixvalley_vendor_app/data/model/response/place_details_model.dart';
import 'package:sixvalley_vendor_app/data/model/response/prediction_model.dart';
import 'package:sixvalley_vendor_app/data/repository/location_repo.dart';
import 'package:sixvalley_vendor_app/main.dart';
import 'package:sixvalley_vendor_app/view/base/custom_snackbar.dart';

class LocationProvider with ChangeNotifier {
  final LocationRepo locationRepo;
  LocationProvider({required this.locationRepo});

  Position _position = Position(longitude: 0, latitude: 0, timestamp: DateTime.now(), accuracy: 1, altitude: 1, heading: 1, speed: 1, speedAccuracy: 1);
  Position _pickPosition = Position(longitude: 0, latitude: 0, timestamp: DateTime.now(), accuracy: 1, altitude: 1, heading: 1, speed: 1, speedAccuracy: 1);
  bool _loading = false;
  bool get loading => _loading;
  bool _isBilling = true;
  bool get isBilling =>_isBilling;
  final TextEditingController _locationController = TextEditingController();

  Position get position => _position;
  Position get pickPosition => _pickPosition;
  String? _address;
  String? _pickAddress;

  String? get address => _address;
  String? get pickAddress => _pickAddress;
  final List<Marker> _markers = <Marker>[];
  TextEditingController get locationController => _locationController;

  List<Marker> get markers => _markers;

  bool _buttonDisabled = true;
  bool _changeAddress = true;
  GoogleMapController? _mapController;
  bool _updateAddAddressData = true;

  bool get buttonDisabled => _buttonDisabled;
  GoogleMapController? get mapController => _mapController;



  void updatePosition(CameraPosition? position, bool fromAddress, String? address, BuildContext context) async {
    if(_updateAddAddressData) {
      _loading = true;
      notifyListeners();
      try {
        if (fromAddress) {
          _position = Position(
            latitude: position!.target.latitude, longitude: position.target.longitude, timestamp: DateTime.now(),
            heading: 1, accuracy: 1, altitude: 1, speedAccuracy: 1, speed: 1,
          );
        } else {
          _pickPosition = Position(
            latitude: position!.target.latitude, longitude: position.target.longitude, timestamp: DateTime.now(),
            heading: 1, accuracy: 1, altitude: 1, speedAccuracy: 1, speed: 1,
          );
        }
        if (_changeAddress) {
            String? addresss = await getAddressFromGeocode(LatLng(position.target.latitude, position.target.longitude), context);
            fromAddress ? _address = addresss : _pickAddress = addresss;

          if(address != null) {
            _locationController.text = address;
          }else if(fromAddress) {
            _locationController.text = addresss;
          }
        } else {
          _changeAddress = true;
        }
      } catch (e) {
        if (kDebugMode) {
          print(e);
        }
      }
      _loading = false;
      notifyListeners();
    }else {
      _updateAddAddressData = true;
    }
  }




  final bool _isAvaibleLocation = false;
  bool get isAvaibleLocation => _isAvaibleLocation;



  bool isLoading = false;
  String? _errorMessage = '';
  String? get errorMessage => _errorMessage;
  String? _addressStatusMessage = '';
  String? get addressStatusMessage => _addressStatusMessage;
  updateAddressStatusMessae({String? message}){
    _addressStatusMessage = message;
  }
  updateErrorMessage({String? message}){
    _errorMessage = message;
  }


  int _selectAddressIndex = 0;

  int get selectAddressIndex => _selectAddressIndex;

  updateAddressIndex(int index, bool notify) {
    _selectAddressIndex = index;
    if(notify) {
      notifyListeners();
    }
  }

  void setLocationIntoSelectLocationScreen( String locationAddress){
    locationController.text = locationAddress;
  }

  String? chosenAddress = '';
  Future<Position> setLocation(String? placeID, String? address, GoogleMapController? mapController) async {
    _loading = true;
    notifyListeners();

    LatLng latLng = const LatLng(0, 0);
    ApiResponse response = await locationRepo.getPlaceDetails(placeID);
    if(response.response?.statusCode == 200) {
      PlaceDetailsModel placeDetails = PlaceDetailsModel.fromJson(response.response?.data);
      if(placeDetails.status == 'OK') {
        latLng = LatLng(placeDetails.result!.geometry!.location!.lat!, placeDetails.result!.geometry!.location!.lng!);
      }
    }

    _pickPosition = Position(
      latitude: latLng.latitude, longitude: latLng.longitude,
      timestamp: DateTime.now(), accuracy: 1, altitude: 1, heading: 1, speed: 1, speedAccuracy: 1,
    );

    chosenAddress = address;
    locationController.text = address??'';
    _changeAddress = false;

    if(mapController != null) {
      mapController.animateCamera(CameraUpdate.newCameraPosition(CameraPosition(target: latLng, zoom: 16)));
    }
    _loading = false;
    notifyListeners();
    return _pickPosition;
  }

  void disableButton() {
    _buttonDisabled = true;
    notifyListeners();
  }

  void setAddAddressData() {
    _position = _pickPosition;
    _address = _pickAddress!;
    _locationController.text = _address??'';
    _updateAddAddressData = false;

  }

  void setPickData() {
    _pickPosition = _position;
    _pickAddress = _address;
    _locationController.text = _address??'';
  }

  void setMapController(GoogleMapController mapController) {
    _mapController = mapController;
  }

  Future<String> getAddressFromGeocode(LatLng latLng, BuildContext context) async {
    ApiResponse response = await locationRepo.getAddressFromGeocode(latLng);
    String address = '';
    if(response.response!.statusCode == 200 && response.response!.data['status'] == 'OK') {
      address = response.response!.data['results'][0]['formatted_address'].toString();
    }else {
      //ApiChecker.checkApi( response);
    }
    return address;
  }


  List<PredictionModel> predictionList = [];
  Future<List<PredictionModel>> searchLocation(BuildContext context, String text) async {
    if(text.isNotEmpty) {
      ApiResponse response = await locationRepo.searchLocation(text);
      if (response.response?.statusCode == 200 && response.response?.data['status'] == 'OK') {
        predictionList = [];
        response.response?.data['predictions'].forEach((prediction) => predictionList.add(PredictionModel.fromJson(prediction)));
      } else {
        showCustomSnackBar(response.response?.data['error_message'] ?? response.response?.statusMessage, Get.context);
      }
    }
    return predictionList;
  }



  String placeMarkToAddress(Placemark placeMark) {
    return '${placeMark.name ?? ''}'
        ' ${placeMark.subAdministrativeArea ?? ''}'
        ' ${placeMark.isoCountryCode ?? ''}';
  }

  void isBillingChanged(bool change) {
    _isBilling = change;
    if (change) {
      change = !_isBilling;
    }
    notifyListeners();
  }


}
