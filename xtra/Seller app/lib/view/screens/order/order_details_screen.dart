import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sixvalley_vendor_app/helper/price_converter.dart';
import 'package:sixvalley_vendor_app/localization/language_constrants.dart';
import 'package:sixvalley_vendor_app/main.dart';
import 'package:sixvalley_vendor_app/provider/order_provider.dart';
import 'package:sixvalley_vendor_app/provider/splash_provider.dart';
import 'package:sixvalley_vendor_app/utill/app_constants.dart';
import 'package:sixvalley_vendor_app/utill/color_resources.dart';
import 'package:sixvalley_vendor_app/utill/dimensions.dart';
import 'package:sixvalley_vendor_app/utill/images.dart';
import 'package:sixvalley_vendor_app/utill/styles.dart';
import 'package:sixvalley_vendor_app/view/base/custom_button.dart';
import 'package:sixvalley_vendor_app/view/base/custom_divider.dart';
import 'package:sixvalley_vendor_app/view/base/custom_image.dart';
import 'package:sixvalley_vendor_app/view/base/image_diaglog.dart';
import 'package:sixvalley_vendor_app/view/base/no_data_screen.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/customer_contact_widget.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/delivery_man_information.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/order_product_list_item.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/order_setup.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/order_top_section.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/payment_status_widget.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/shipping_and_biilling_widget.dart';
import 'package:sixvalley_vendor_app/view/screens/order/widget/third_party_delivery_info.dart';


class OrderDetailsScreen extends StatefulWidget {
  final int? orderId;
  final bool fromNotification;
  const OrderDetailsScreen({Key? key,  required this.orderId, this.fromNotification = false}) : super(key: key);

  @override
  State<OrderDetailsScreen> createState() => _OrderDetailsScreenState();
}

class _OrderDetailsScreenState extends State<OrderDetailsScreen> {
  final GlobalKey<ScaffoldState> _scaffoldKey = GlobalKey<ScaffoldState>();
  void _loadData(BuildContext context) async {
    if(widget.fromNotification) {
      await Provider.of<SplashProvider>(Get.context!, listen: false).initConfig();
    }
    Provider.of<OrderProvider>(Get.context!, listen: false).getOrderDetails(widget.orderId.toString());
    Provider.of<OrderProvider>(Get.context!, listen: false).initOrderStatusList(
        Provider.of<SplashProvider>(Get.context!, listen: false).configModel!.shippingMethod == 'inhouse_shipping' ?  'inhouse_shipping':"seller_wise");

  }


  bool _onlyDigital = true;

  @override
  void initState() {

    _loadData(context);
    // Provider.of<OrderProvider>(context, listen: false).setPaymentStatus(widget.orderModel!.paymentStatus);
    // Provider.of<OrderProvider>(context, listen: false).updateStatus(widget.orderModel!.orderStatus, notify: false);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      key: _scaffoldKey,

      appBar: AppBar(elevation: 1,
        backgroundColor: Theme.of(context).cardColor, toolbarHeight: 120,
          leadingWidth: 0, automaticallyImplyLeading: false,
          title: Consumer<OrderProvider>(
            builder: (context, orderProvider,_) {
              return OrderTopSection(orderModel: orderProvider.orderDetails?[0].order);
            }
          )),

      body: RefreshIndicator(
        onRefresh: () async => _loadData(context),
        child: Consumer<OrderProvider>(
            builder: (context, orderProvider, child) {
              double itemsPrice = 0;
              double discount = 0;
              double? eeDiscount = 0;
              double tax = 0;
              double coupon = 0;
              double shipping = 0;


              if (orderProvider.orderDetails != null) {
                 coupon = orderProvider.orderDetails![0].order!.discountAmount!;
                 shipping = orderProvider.orderDetails![0].order!.shippingCost!;
                for (var orderDetails in orderProvider.orderDetails!) {
                  if(orderDetails.productDetails?.productType == "physical"){
                    _onlyDigital =  false;
                  }
                  itemsPrice = itemsPrice + (orderDetails.price! * orderDetails.qty!);
                  discount = discount + orderDetails.discount!;
                    tax = tax + orderDetails.tax!;

                }
                 if(orderProvider.orderDetails![0].order!.orderType == 'POS'){
                   if(orderProvider.orderDetails![0].order!.extraDiscountType == 'percent'){
                     eeDiscount = itemsPrice * (orderProvider.orderDetails![0].order!.extraDiscount!/100);
                   }else{
                     eeDiscount = orderProvider.orderDetails![0].order!.extraDiscount;
                   }
                 }
              }
              double subTotal = itemsPrice +tax - discount;

              double totalPrice = subTotal + shipping - coupon - eeDiscount!;

              return orderProvider.orderDetails != null ? orderProvider.orderDetails!.isNotEmpty ?
              CustomScrollView(slivers: [
                  SliverToBoxAdapter(child:
                  Column(children: [

                    Container(height: 10, color: Theme.of(context).primaryColor.withOpacity(.1)),

                    Container(decoration: BoxDecoration(color: Theme.of(context).cardColor, boxShadow: ThemeShadow.getShadow(context)),
                        child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [

                          if(Provider.of<SplashProvider>(context, listen: false).configModel?.orderVerification == 1 &&  orderProvider.orderDetails![0].order!.orderType != 'POS')
                            Padding(padding: const EdgeInsets.symmetric(vertical: Dimensions.paddingSizeDefault),
                              child: Center(child: Text.rich(TextSpan(children: [
                                TextSpan(text: '${getTranslated('order_verification_code', context)} : ', style: robotoRegular.copyWith(color: Theme.of(context).hintColor)),
                                TextSpan(text: orderProvider.orderDetails![0].order?.verificationCode??'', style: robotoBold.copyWith(color: Theme.of(context).primaryColor)),
                              ])),),
                            ),

                          orderProvider.orderDetails![0].order!.orderType == 'POS'? const SizedBox():
                          ShippingAndBillingWidget(orderModel: orderProvider.orderDetails![0].order!, onlyDigital: _onlyDigital, orderType: orderProvider.orderDetails![0].order!.orderType!),



                          Column(crossAxisAlignment: CrossAxisAlignment.start, children: [

                            Container(padding: const EdgeInsets.fromLTRB( Dimensions.paddingSizeSmall,
                                Dimensions.paddingSizeDefault, Dimensions.paddingSizeDefault,0),

                              child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                                Row(children: [
                                  SizedBox(width: 15, child: Image.asset(Images.orderSummery)),
                                  const SizedBox(width: Dimensions.paddingSizeExtraSmall),
                                  Text(getTranslated('order_summery', context)!,
                                      style: titilliumSemiBold.copyWith(fontSize: Dimensions.fontSizeLarge) ),
                                ],
                                ),
                                const SizedBox(height: Dimensions.paddingSizeDefault,),


                                ListView.builder(
                                  padding: const EdgeInsets.all(0),
                                  shrinkWrap: true,
                                  physics: const NeverScrollableScrollPhysics(),
                                  itemCount: orderProvider.orderDetails!.length,
                                  itemBuilder: (context, index) {
                                    return OrderedProductListItem(orderDetailsModel: orderProvider.orderDetails![index],
                                      paymentStatus: orderProvider.paymentStatus,orderId: widget.orderId,
                                      index: index, length: orderProvider.orderDetails!.length,
                                    );
                                  },
                                ),

                              ],
                              ),),


                            orderProvider.orderDetails![0].order!.orderNote != null?
                            Container(decoration: BoxDecoration(
                                color: Theme.of(context).cardColor,
                                boxShadow: [BoxShadow(color: Theme.of(context).hintColor.withOpacity(.25),spreadRadius: .11,blurRadius: .11, offset: const Offset(0,2))],
                                borderRadius: const BorderRadius.only(bottomLeft: Radius.circular(Dimensions.paddingSizeSmall),
                                    bottomRight: Radius.circular(Dimensions.paddingSizeSmall))),
                              child: Container(decoration: BoxDecoration(
                                color: Theme.of(context).hintColor.withOpacity(.07),
                                borderRadius: const BorderRadius.only(bottomLeft: Radius.circular(Dimensions.paddingSizeSmall),
                                    bottomRight: Radius.circular(Dimensions.paddingSizeSmall)),),
                                padding: const EdgeInsets.fromLTRB( Dimensions.paddingSizeDefault,Dimensions.paddingSizeDefault,Dimensions.paddingSizeDefault,
                                    Dimensions.paddingSizeDefault),

                                child: Column(crossAxisAlignment: CrossAxisAlignment.start,children: [
                                  Row(children: [
                                    Padding(padding: const EdgeInsets.only(right: Dimensions.paddingSizeSmall),
                                      child: Image.asset(Images.orderNote,color: ColorResources.getTextColor(context), width: Dimensions.iconSizeSmall ),),
                                    Text(getTranslated('order_note', context)!, style: titilliumSemiBold.copyWith(fontSize: Dimensions.fontSizeLarge,
                                      color: ColorResources.titleColor(context),)),
                                  ],
                                  ),
                                  const SizedBox(height: Dimensions.paddingSizeExtraSmall),

                                  Text(orderProvider.orderDetails![0].order!.orderNote != null? orderProvider.orderDetails![0].order!.orderNote ?? '': "",
                                      style: titilliumRegular.copyWith(color: ColorResources.getTextColor(context))),
                                ],),
                              ),
                            ):const SizedBox(),



                            Container(padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault,
                                vertical: Dimensions.paddingSizeDefault),
                              child: Column(children: [

                                // Total
                                Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('sub_total', context)!,
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),


                                  Text(PriceConverter.convertPrice(context, itemsPrice),
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),]),
                                const SizedBox(height: Dimensions.paddingSizeSmall,),



                                Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('tax', context)!,
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),


                                  Text('+ ${PriceConverter.convertPrice(context, tax)}',
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),]),
                                const SizedBox(height: Dimensions.paddingSizeSmall,),




                                Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('discount', context)!,
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),


                                  Text('- ${PriceConverter.convertPrice(context, discount)}',
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),]),
                                const SizedBox(height: Dimensions.paddingSizeSmall,),



                                orderProvider.orderDetails![0].order!.orderType == "POS"?
                                Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('extra_discount', context)!,
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),
                                  Text('- ${PriceConverter.convertPrice(context, eeDiscount)}',
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),
                                ]):const SizedBox(),
                                SizedBox(height:  orderProvider.orderDetails![0].order!.orderType == "POS"? Dimensions.paddingSizeSmall: 0),


                                Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('coupon_discount', context)!,
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),
                                  Text('- ${PriceConverter.convertPrice(context, coupon)}',
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),]),
                                const SizedBox(height: Dimensions.paddingSizeSmall,),



                                if(!_onlyDigital)Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('shipping_fee', context)!,
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),
                                  Text('+ ${PriceConverter.convertPrice(context, shipping)}',
                                      style: titilliumRegular.copyWith(
                                          color: ColorResources.titleColor(context))),]),


                                const Padding(padding: EdgeInsets.symmetric(vertical: Dimensions.paddingSizeSmall),
                                    child: CustomDivider()),




                                Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
                                  Text(getTranslated('total_amount', context)!,
                                      style: titilliumSemiBold.copyWith(fontSize: Dimensions.fontSizeDefault)),
                                  Text(PriceConverter.convertPrice(context, totalPrice),
                                    style: titilliumSemiBold.copyWith(fontSize: Dimensions.fontSizeExtraLarge,
                                        color: Theme.of(context).primaryColor),),]),
                              ],),),
                          ]),




                          PaymentStatusWidget(order: orderProvider, orderModel: orderProvider.orderDetails![0].order!,),




                          CustomerContactWidget(orderModel: orderProvider.orderDetails![0].order),

                          const SizedBox(height: Dimensions.paddingSizeExtraSmall),


                          orderProvider.orderDetails![0].order!.deliveryMan != null?
                          Padding(padding: const EdgeInsets.only(bottom: Dimensions.paddingSizeSmall),
                            child: DeliveryManContactInformation(orderModel: orderProvider.orderDetails![0].order, orderType: orderProvider.orderDetails![0].order!.orderType, onlyDigital: _onlyDigital),
                          ):const SizedBox(),

                          orderProvider.orderDetails![0].order!.thirdPartyServiceName != null?
                          Padding(padding: const EdgeInsets.only(bottom: Dimensions.paddingSizeSmall),
                            child: ThirdPartyDeliveryInfo(orderModel: orderProvider.orderDetails![0].order),
                          ):const SizedBox.shrink(),

                          if(orderProvider.orderDetails != null && orderProvider.orderDetails![0].verificationImages != null && orderProvider.orderDetails![0].verificationImages!.isNotEmpty)
                            Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
                              Padding(padding: const EdgeInsets.fromLTRB(Dimensions.paddingSizeSmall,  Dimensions.paddingSizeSmall, Dimensions.paddingSizeSmall, Dimensions.paddingSizeSmall),
                                child: Text('${getTranslated('completed_service_picture', context)}',
                                  style: robotoMedium.copyWith(fontSize: Dimensions.fontSizeDefault, color: Theme.of(context).hintColor),),),

                              SizedBox(height: 120,
                                child: ListView.builder(
                                    itemCount: orderProvider.orderDetails![0].verificationImages?.length,
                                    scrollDirection: Axis.horizontal,
                                    itemBuilder: (context, index){

                                      return InkWell(onTap: () => showDialog(context: context, builder: (_)=> ImageDialog(imageUrl: '${AppConstants.baseUrl}/storage/app/public/delivery-man/verification-image/${orderProvider.orderDetails![0].verificationImages?[index].image}')),
                                        child: Padding(padding:  EdgeInsets.only(left: Dimensions.paddingSizeSmall,
                                            right: orderProvider.orderDetails![0].verificationImages!.length == index+1? Dimensions.paddingSizeSmall : 0),
                                          child: SizedBox(width: 200,
                                            child: ClipRRect(borderRadius: BorderRadius.circular(Dimensions.paddingSizeSmall),
                                              child: Container(decoration: BoxDecoration(
                                                  border: Border.all(color: Theme.of(context).primaryColor.withOpacity(.25), width: .25),
                                                  borderRadius: BorderRadius.circular(Dimensions.paddingSizeSmall)),
                                                  child: CustomImage(image: '${AppConstants.baseUrl}/storage/app/public/delivery-man/verification-image/${orderProvider.orderDetails![0].verificationImages?[index].image}')),
                                            ),
                                          ),
                                        ),
                                      );
                                    }),
                              ),
                            ],
                            ),


                        ],),),

                    const SizedBox(height: Dimensions.paddingSizeSmall),
                    ],
                  ),)
                ],

              ):
              const NoDataScreen() :
              const Center(child: CircularProgressIndicator());
            }
        ),
      ),

      bottomNavigationBar: Consumer<OrderProvider>(
        builder: (context, orderProvider, _) {
          return (orderProvider.orderDetails != null && orderProvider.orderDetails![0].order!.orderType =='POS')? const SizedBox.shrink(): Container(
            decoration: BoxDecoration(color: Theme.of(context).cardColor,
              boxShadow: ThemeShadow.getShadow(context)),

              padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault, vertical: Dimensions.paddingSizeSmall),
              child: CustomButton(borderRadius: Dimensions.paddingSizeExtraSmall,
                btnTxt: getTranslated('order_setup', context),
                onTap: (){Navigator.push(context, MaterialPageRoute(builder: (_) =>
                    OrderSetup(orderModel: orderProvider.orderDetails![0].order,onlyDigital: _onlyDigital)));
                },
              ));
        }
      ),
    );
  }
}
