import 'package:sixvalley_vendor_app/data/model/response/chat_model.dart';

class MessageModel {
  int? totalSize;
  String? limit;
  String? offset;
  List<Message>? message;

  MessageModel({this.totalSize, this.limit, this.offset, this.message});

  MessageModel.fromJson(Map<String, dynamic> json) {
    totalSize = json['total_size'];
    limit = json['limit'];
    offset = json['offset'];
    if (json['message'] != null) {
      message = <Message>[];
      json['message'].forEach((v) {
        message!.add(Message.fromJson(v));
      });
    }
  }

}

class Message {
  int? id;
  int? userId;
  int? deliveryManId;
  String? message;
  int? sentByCustomer;
  int? sentByDeliveryMan;
  int? sentBySeller;
  int? seenBySeller;
  String? createdAt;
  String? updatedAt;
  Customer? customer;
  DeliveryMan? deliveryMan;
  List<String>? attachment;

  Message(
      {this.id,
        this.userId,
        this.deliveryManId,
        this.message,
        this.sentByCustomer,
        this.sentByDeliveryMan,
        this.sentBySeller,
        this.seenBySeller,
        this.createdAt,
        this.updatedAt,
        this.customer,
      this.deliveryMan,
        this.attachment
      });

  Message.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    userId = json['user_id'];
    if(json['delivery_man_id'] != null){
      deliveryManId = int.parse(json['delivery_man_id'].toString());
    }

    message = json['message'];
    sentByCustomer = json['sent_by_customer'];
    if(json['sent_by_delivery_man'] != null){
      sentByDeliveryMan = int.parse(json['sent_by_delivery_man'].toString());
    }

    sentBySeller = json['sent_by_seller'];
    seenBySeller = json['seen_by_seller'];
    createdAt = json['created_at'];
    updatedAt = json['updated_at'];
    customer = json['customer'] != null ? Customer.fromJson(json['customer']) : null;
    deliveryMan = json['delivery_man'] != null ? DeliveryMan.fromJson(json['delivery_man']) : null;
    if(json['attachment'] != null){
      attachment = json['attachment'].cast<String>();
    }else{
      attachment = [];
    }
  }

}


