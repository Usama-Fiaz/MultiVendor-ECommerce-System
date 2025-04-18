import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:provider/provider.dart';
import 'package:sixvalley_vendor_app/localization/language_constrants.dart';
import 'package:sixvalley_vendor_app/provider/location_provider.dart';
import 'package:sixvalley_vendor_app/provider/order_provider.dart';
import 'package:sixvalley_vendor_app/utill/color_resources.dart';
import 'package:sixvalley_vendor_app/utill/dimensions.dart';
import 'package:sixvalley_vendor_app/utill/styles.dart';
import 'package:sixvalley_vendor_app/view/base/custom_app_bar.dart';
import 'package:sixvalley_vendor_app/view/base/custom_button.dart';
import 'package:sixvalley_vendor_app/view/base/textfeild/custom_text_feild.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/select_location_screen.dart';


class EditAddress extends StatefulWidget {
  final bool? isBilling;
  final String? address;
  final String? city;
  final String? zip;
  final String? name;
  final String? number;
  final String? email;
  final String? orderId;
  final String? lat;
  final String? lng;

   const EditAddress({Key? key, this.isBilling, this.address, this.city, this.zip, this.name, this.number, this.orderId, this.email, this.lat, this.lng}) : super(key: key);

  @override
  State<EditAddress> createState() => EditAddressState();
}

class EditAddressState extends State<EditAddress> {

  final TextEditingController _contactPersonNameController = TextEditingController();
  final TextEditingController _contactPersonEmailController = TextEditingController();
  final TextEditingController _contactPersonNumberController = TextEditingController();
  final TextEditingController _cityController = TextEditingController();
  final TextEditingController _zipCodeController = TextEditingController();
  final FocusNode _addressNode = FocusNode();
  final FocusNode _nameNode = FocusNode();
  final FocusNode _emailNode = FocusNode();
  final FocusNode _numberNode = FocusNode();
  final FocusNode _cityNode = FocusNode();
  final FocusNode _zipNode = FocusNode();
  bool _updateAddress = true;
  CameraPosition? _cameraPosition;
  GoogleMapController? _controller;

  @override
  void initState() {
    super.initState();
    Provider.of<LocationProvider>(context, listen: false).locationController.text = widget.address??'';
    _cityController.text = widget.city??'';
    _zipCodeController.text = widget.zip??'';
    _contactPersonNameController.text = widget.name??'';
    _contactPersonNumberController.text = widget.number??'';
    _contactPersonEmailController.text = widget.email??'';
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: CustomAppBar(title: getTranslated('update_address', context)),
      body: SingleChildScrollView(
        child: Consumer<OrderProvider>(
          builder: (context, orderProvider,_) {
            return Consumer<LocationProvider>(
              builder: (context, locationProvider,_ ) {
                return Container(padding:  const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault),
                  child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [

                    const SizedBox(height: Dimensions.paddingSizeDefault),
                    SizedBox(height: MediaQuery.of(context).size.width/2, width: MediaQuery.of(context).size.width,
                      child: ClipRRect(borderRadius: BorderRadius.circular(Dimensions.paddingSizeSmall),
                        child: Stack(clipBehavior: Clip.none, children: [
                          GoogleMap(mapType: MapType.normal,
                            initialCameraPosition:  CameraPosition(target: LatLng(double.parse(widget.lat.toString()), double.parse(widget.lng.toString())), zoom: 16),
                            onTap: (latLng) {
                             Navigator.of(context).push(MaterialPageRoute(builder: (BuildContext context) => SelectLocationScreen(googleMapController: _controller)));
                            },
                            zoomControlsEnabled: false,
                            compassEnabled: false,
                            indoorViewEnabled: true,
                            mapToolbarEnabled: false,
                            onCameraIdle: () {
                              if(_updateAddress) {
                                locationProvider.updatePosition(_cameraPosition, true, null, context);
                              }else {
                                _updateAddress = true;
                              }
                            },
                            onCameraMove: ((position) => _cameraPosition = position),
                            onMapCreated: (GoogleMapController controller) {

                            },
                          ),

                          Container(width: MediaQuery.of(context).size.width, alignment: Alignment.center,
                              height: MediaQuery.of(context).size.height,
                              child: Icon(Icons.location_on, size: 40, color: Theme.of(context).primaryColor,)),
                          Align(alignment: Alignment.topRight,
                            child: InkWell(onTap: (){

                              Navigator.of(context).push(MaterialPageRoute(builder: (BuildContext context) => SelectLocationScreen(googleMapController: _controller)));
                            },
                              child: Padding(padding: const EdgeInsets.all(Dimensions.paddingSize),
                                child: Container(width: 40, alignment: Alignment.center,
                                    height: 40,
                                    decoration: BoxDecoration(color: Theme.of(context).cardColor, borderRadius: BorderRadius.circular(Dimensions.paddingSizeExtraSmall)),
                                    child: Icon(Icons.fullscreen, size: 30, color: Theme.of(context).primaryColor,)),
                              ),
                            ),
                          ),

                        ],
                        ),
                      ),
                    ),

                      Padding(padding: const EdgeInsets.only(top: 5,),
                        child: Text(getTranslated('delivery_address', context)!,
                          style: Theme.of(context).textTheme.displaySmall!.copyWith(color: ColorResources.getHint(context), fontSize: Dimensions.fontSizeLarge))),


                      const SizedBox(height: Dimensions.paddingSizeSmall),
                      CustomTextField(
                        border: true,
                        hintText: getTranslated('address_line_02', context),
                        textInputType: TextInputType.streetAddress,
                        textInputAction: TextInputAction.next,
                        focusNode: _addressNode,
                        nextNode: _cityNode,
                        controller: locationProvider.locationController),

                      const SizedBox(height: Dimensions.paddingSizeDefault),
                      Text(getTranslated('city', context)!,
                        style: robotoRegular.copyWith(color: ColorResources.getHint(context))),

                      const SizedBox(height: Dimensions.paddingSizeSmall),
                      CustomTextField(
                          border: true,
                        hintText: getTranslated('city', context),
                        textInputType: TextInputType.streetAddress,
                        textInputAction: TextInputAction.next,
                        focusNode: _cityNode,
                        nextNode: _zipNode,
                        controller: _cityController),
                      const SizedBox(height: Dimensions.paddingSizeDefault),

                      Text(getTranslated('zip', context)!,
                        style: robotoRegular.copyWith(color: ColorResources.getHint(context))),
                      const SizedBox(height: Dimensions.paddingSizeSmall),

                      CustomTextField(
                          border: true,
                        hintText: getTranslated('zip', context),
                        textInputAction: TextInputAction.next,
                        focusNode: _zipNode,
                        nextNode: _nameNode,
                        controller: _zipCodeController),

                      const SizedBox(height: Dimensions.paddingSizeDefault),

                      Text(getTranslated('contact_person_name', context)!,
                        style: robotoRegular.copyWith(color: ColorResources.getHint(context))),
                      const SizedBox(height: Dimensions.paddingSizeSmall),

                    CustomTextField(
                      border: true,
                        hintText: getTranslated('enter_contact_person_name', context),
                        textInputType: TextInputType.name,
                        controller: _contactPersonNameController,
                        focusNode: _nameNode,
                        nextNode: _emailNode,
                        textInputAction: TextInputAction.next,
                        capitalization: TextCapitalization.words,
                      ),

                    const SizedBox(height: Dimensions.paddingSizeDefault),



                    Text(getTranslated('contact_person_number', context)!,
                        style: robotoRegular.copyWith(color: ColorResources.getHint(context)),),
                      const SizedBox(height: Dimensions.paddingSizeSmall),

                    CustomTextField(
                      border: true,
                        hintText: getTranslated('enter_contact_person_number', context),
                        textInputType: TextInputType.phone,
                        textInputAction: TextInputAction.done,
                        focusNode: _numberNode,
                        controller: _contactPersonNumberController,
                      ),

                      const SizedBox(height: Dimensions.paddingSizeDefault),

                      Container(height: 50.0,
                        margin: const EdgeInsets.all(Dimensions.paddingSizeSmall),
                        child: CustomButton(
                          btnTxt: getTranslated('update_address', context),
                          onTap: () {
                            String contactPersonName = _contactPersonNameController.text.trim();
                            String phone = _contactPersonNumberController.text.trim();
                            String email = _contactPersonEmailController.text.trim();
                            String city = _cityController.text.trim();
                            String zip = _zipCodeController.text.trim();
                            String addressType = widget.isBilling! ? 'billing':'shipping';
                            String address =  locationProvider.locationController.text.trim();
                            orderProvider.editShippingAndBillingAddress(orderID: widget.orderId, addressType: addressType, contactPersonName: contactPersonName,
                            phone: phone, city: city, zip: zip, address:address, email: email, latitude: widget.lat, longitude: widget.lng);
                          },
                        )
                      )
                    ],
                  ),
                );
              }
            );
          }
        ),
      ),
    );
  }

}

enum Address {shipping, billing }