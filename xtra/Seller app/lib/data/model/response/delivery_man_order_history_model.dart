import 'package:sixvalley_vendor_app/data/model/response/order_model.dart';

class DeliveryManOrderHistory {
  int? totalSize;
  String? limit;
  String? offset;
  List<Order>? orders;

  DeliveryManOrderHistory(
      {this.totalSize, this.limit, this.offset, this.orders});

  DeliveryManOrderHistory.fromJson(Map<String, dynamic> json) {
    totalSize = json['total_size'];
    limit = json['limit'];
    offset = json['offset'];
    if (json['orders'] != null) {
      orders = <Order>[];
      json['orders'].forEach((v) {
        orders!.add(Order.fromJson(v));
      });
    }
  }

}


