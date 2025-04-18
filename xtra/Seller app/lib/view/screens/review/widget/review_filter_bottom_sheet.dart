import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sixvalley_vendor_app/localization/language_constrants.dart';
import 'package:sixvalley_vendor_app/provider/cart_provider.dart';
import 'package:sixvalley_vendor_app/provider/product_provider.dart';
import 'package:sixvalley_vendor_app/provider/product_review_provider.dart';
import 'package:sixvalley_vendor_app/utill/dimensions.dart';
import 'package:sixvalley_vendor_app/utill/images.dart';
import 'package:sixvalley_vendor_app/utill/styles.dart';
import 'package:sixvalley_vendor_app/view/base/custom_button.dart';
import 'package:sixvalley_vendor_app/view/base/custom_container.dart';
import 'package:sixvalley_vendor_app/view/base/custom_date_picker.dart';
import 'package:sixvalley_vendor_app/view/base/custom_drop_down_item.dart';
import 'package:sixvalley_vendor_app/view/screens/pos/customer_search_screen.dart';
import 'package:sixvalley_vendor_app/view/screens/review/widget/review_product_filter_widget.dart';




class ReviewFilterBottomSheet extends StatefulWidget {
  const ReviewFilterBottomSheet({Key? key}) : super(key: key);

  @override
  State<ReviewFilterBottomSheet> createState() => _ReviewFilterBottomSheetState();
}

class _ReviewFilterBottomSheetState extends State<ReviewFilterBottomSheet> {

  @override
  void initState() {

    super.initState();
  }
  @override
  Widget build(BuildContext context) {
    return Padding(padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
      child: Consumer<ProductReviewProvider>(
        builder: (context, reviewProvider,_) {
          return Consumer<ProductProvider>(
            builder: (context, productProvider,_) {
              return Container(decoration: BoxDecoration(color: Theme.of(context).cardColor,
                  borderRadius: const BorderRadius.only(topLeft: Radius.circular(Dimensions.paddingSizeDefault),
                      topRight: Radius.circular(Dimensions.paddingSizeDefault))),

                child: Column(mainAxisSize: MainAxisSize.min, children: [

                    Padding(padding: const EdgeInsets.only(bottom: Dimensions.paddingSizeDefault,
                      top: Dimensions.paddingSizeExtraLarge,),
                      child: Text(getTranslated('filter_date', context)!, style: robotoBold.copyWith(fontSize: Dimensions.fontSizeLarge),),),

                    if(productProvider.sellerProductModel != null && productProvider.sellerProductModel!.products != null && productProvider.sellerProductModel!.products!.isNotEmpty)


                  CustomContainer(title: productProvider.selectedProductName, onTap: (){
                    showDialog(context: context, barrierDismissible: true,
                        builder: (_) => const ReviewProductFilterWidget());
                  }),

                  Consumer<CartProvider>(
                    builder: (context,customerController,_) {
                      return InkWell(
                          onTap: ()=> Navigator.push(context, MaterialPageRoute(builder: (_)=> const CustomerSearchScreen())),
                          child: Padding(
                            padding: const EdgeInsets.symmetric(horizontal: Dimensions.paddingSizeDefault),
                            child: ClipRRect(borderRadius: BorderRadius.circular(Dimensions.paddingSizeExtraSmall),
                              child: Container(width: MediaQuery.of(context).size.width,
                                  decoration: BoxDecoration(
                                      border: Border.all(width: .25, color: Theme.of(context).hintColor.withOpacity(.75)),
                                      color: Theme.of(context).cardColor,
                                      boxShadow: ThemeShadow.getShadow(context),
                                      borderRadius: BorderRadius.circular(Dimensions.paddingSizeExtraSmall)
                                  ),
                                  child: Padding(padding: const EdgeInsets.all(Dimensions.paddingSize),
                                    child: Row(children: [
                                        Expanded(child: Text(customerController.searchCustomerController.text.trim().isNotEmpty?
                                        customerController.searchCustomerController.text: '${getTranslated('select_customer', context)}')),
                                      const Icon(Icons.arrow_drop_down_sharp)
                                      ],
                                    ),
                                  )),
                            ),
                          ));
                    }
                  ),


                    CustomDropDownItem(
                      widget: DropdownButtonFormField<String>(
                        value: reviewProvider.reviewStatusName,
                        isExpanded: true,
                        decoration: const InputDecoration(border: InputBorder.none),
                        iconSize: 24, elevation: 16, style: robotoRegular,
                        onChanged: (value){
                          reviewProvider.setReviewStatusIndex(value == 'select_status'?0:value == 'Active'? 1:2);
                        },
                        items: reviewProvider.reviewStatusList.map<DropdownMenuItem<String>>((String value) {
                          return DropdownMenuItem<String>(
                            value: value,
                            child: Text(getTranslated(value, context)!,
                                style: robotoRegular.copyWith(color: Theme.of(context).textTheme.bodyLarge!.color)),
                          );
                        }).toList(),
                      ),

                    ),

                    Row(children: [
                      Expanded(child: CustomDatePicker(
                        title: getTranslated('from', context),
                        image: Images.calenderIcon,
                        text: reviewProvider.startDate != null ?
                        reviewProvider.dateFormat.format(reviewProvider.startDate!).toString() : getTranslated('select_date', context),
                        selectDate: () => reviewProvider.selectDate("start", context),
                      )),
                      Expanded(child: CustomDatePicker(
                        title: getTranslated('to', context),
                        image: Images.calenderIcon,
                        text: reviewProvider.endDate != null ?
                        reviewProvider.dateFormat.format(reviewProvider.endDate!).toString() : getTranslated('select_date', context),
                        selectDate: () => reviewProvider.selectDate("end", context),
                      )),
                    ],),
                    const SizedBox(height: Dimensions.paddingSizeDefault,),
                    Padding(
                      padding: const EdgeInsets.all(Dimensions.paddingSizeDefault),
                      child: CustomButton(btnTxt: getTranslated('search', context),
                      onTap: (){
                        int?  productId = Provider.of<ProductProvider>(context, listen: false).selectedProductId;
                        int?  customerId = Provider.of<CartProvider>(context, listen: false).customerId;
                        reviewProvider.filterReviewList(context, productId, customerId);
                      },),
                    ),
                    const SizedBox(height: Dimensions.paddingSizeDefault,),


              ],),);
            }
          );
        }
      ),
    );
  }
}

