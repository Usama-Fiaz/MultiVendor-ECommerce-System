import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sixvalley_vendor_app/provider/product_provider.dart';
import 'package:sixvalley_vendor_app/provider/profile_provider.dart';
import 'package:sixvalley_vendor_app/utill/dimensions.dart';
import 'package:sixvalley_vendor_app/view/base/paginated_list_view.dart';

class ReviewProductFilterWidget extends StatefulWidget {
  const ReviewProductFilterWidget({Key? key}) : super(key: key);

  @override
  State<ReviewProductFilterWidget> createState() => _ReviewProductFilterWidgetState();
}

class _ReviewProductFilterWidgetState extends State<ReviewProductFilterWidget> {
  ScrollController scrollController = ScrollController();
  @override
  Widget build(BuildContext context) {
    return Dialog(
      child: SizedBox(
        height: MediaQuery.of(context).size.height * .65,
        child: Consumer<ProductProvider>(
          builder: (context, productProvider,_) {
            return SingleChildScrollView(
              controller: scrollController,
              child: PaginatedListView(scrollController: scrollController,
                  onPaginate: (int? offset) async{
                await productProvider.initSellerProductList(Provider.of<ProfileProvider>(context, listen: false).userInfoModel!.id.toString(), offset!, context, 'en','', reload: true);
                },
                  totalSize: productProvider.sellerProductModel!.totalSize,
                  offset: productProvider.sellerProductModel!.offset,
                  itemView: ListView.builder(
                      itemCount: productProvider.sellerProductModel!.products?.length,
                      padding: EdgeInsets.zero,
                      physics: const NeverScrollableScrollPhysics(),
                      shrinkWrap: true,
                      itemBuilder: (context, index){
                        return InkWell(onTap: (){
                          productProvider.setReviewProductIndex(index, productProvider.sellerProductModel!.products?[index].id, productProvider.sellerProductModel!.products![index].name, true);
                        },
                          child: Padding(padding: const EdgeInsets.all(Dimensions.paddingSizeSmall),
                            child: Text(productProvider.sellerProductModel!.products?[index].name??''),
                          ),
                        );
                      })),
            );
          }
        ),
      ),
    );
  }
}
