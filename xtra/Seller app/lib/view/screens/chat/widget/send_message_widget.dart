import 'package:emoji_picker_flutter/emoji_picker_flutter.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sixvalley_vendor_app/data/model/body/message_body.dart';
import 'package:sixvalley_vendor_app/localization/language_constrants.dart';
import 'package:sixvalley_vendor_app/provider/chat_provider.dart';
import 'package:sixvalley_vendor_app/utill/color_resources.dart';
import 'package:sixvalley_vendor_app/utill/dimensions.dart';
import 'package:sixvalley_vendor_app/utill/images.dart';
import 'package:sixvalley_vendor_app/utill/styles.dart';
import 'package:flutter/foundation.dart' as foundation;


class SendMessageWidget extends StatefulWidget {
  final int? id;
  const SendMessageWidget({Key? key, this.id}) : super(key: key);

  @override
  State<SendMessageWidget> createState() => _SendMessageWidgetState();
}

class _SendMessageWidgetState extends State<SendMessageWidget> {
  final TextEditingController _controller = TextEditingController();
  bool emojiPicker = false;
  @override
  Widget build(BuildContext context) {
    return Consumer<ChatProvider>(
      builder: (context, chatProvider, _) {
        return Column(children: [
            SizedBox(height: 65,
              child: Card(color: Theme.of(context).highlightColor,
                shadowColor: Colors.grey[200],
                elevation: 2,
                margin: const EdgeInsets.all(Dimensions.paddingSizeSmall),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                child: Container(decoration: BoxDecoration(border: Border.all(color: Theme.of(context).primaryColor,width: 1),
                    borderRadius: BorderRadius.circular(50)),
                  child: Padding(padding: const EdgeInsets.only(right: Dimensions.paddingSizeSmall),
                    child: Row(children: [
                      Expanded(
                        child: TextField(
                          controller: _controller,
                          style: titilliumRegular,
                          keyboardType: TextInputType.multiline,
                          maxLines: null,
                          expands: true,
                          onTap: (){
                            setState(() {
                              emojiPicker = false;
                            });
                          },
                          decoration: InputDecoration(
                            hintText: getTranslated('type_here', context),
                            hintStyle: titilliumRegular.copyWith(color: ColorResources.hintTextColor),
                            border: InputBorder.none,


                            prefixIcon: GestureDetector(onTap: (){
                              setState(() {
                                emojiPicker = !emojiPicker;
                                FocusManager.instance.primaryFocus?.unfocus();
                              });
                            },
                              child: Padding(padding: const EdgeInsets.symmetric(vertical: 8.0),
                                child: Image.asset(Images.emoji),),
                            ),

                            suffixIcon: GestureDetector(onTap: ()=> chatProvider.pickMultipleImage(false),

                              child: Padding(padding: const EdgeInsets.all(Dimensions.paddingSeven),
                                child: Image.asset(Images.attachment),),
                            )
                          ),
                          onChanged: (String newText) {
                            if(newText.isNotEmpty && !chatProvider.isSendButtonActive) {
                              chatProvider.toggleSendButtonActivity();
                            }else if(newText.isEmpty && chatProvider.isSendButtonActive) {
                              chatProvider.toggleSendButtonActivity();
                            }
                          },
                        ),
                      ),


                      chatProvider.isSending? const Center(child: SizedBox(width: 30,height: 30,child: CircularProgressIndicator())):
                      GestureDetector(
                        onTap: () {
                          if(_controller.text.trim().isNotEmpty || chatProvider.pickedImageFileStored!.isNotEmpty){
                            MessageBody messageBody = MessageBody(sellerId: widget.id, message: _controller.text.trim());
                            chatProvider.sendMessage(messageBody).then((value){
                              if(value.statusCode == 200){
                                _controller.clear();
                              }
                            });

                          }
                        },
                        child: SizedBox(width: Dimensions.iconSizeLarge,height: Dimensions.iconSizeLarge,
                          child: Image.asset(Images.send,
                            color: chatProvider.isSendButtonActive ? Theme.of(context).primaryColor : ColorResources.hintTextColor),
                        ),
                      ),
                    ]),
                  ),
                ),
              ),
            ),
            if(emojiPicker)
              SizedBox(height: 250,
                child: EmojiPicker(
                  onBackspacePressed: () {
                  },
                  textEditingController: _controller,
                  config: Config(
                    columns: 7,
                    emojiSizeMax: 32 * (foundation.defaultTargetPlatform == TargetPlatform.iOS ? 1.30 : 1.0),
                    verticalSpacing: 0,
                    horizontalSpacing: 0,
                    gridPadding: EdgeInsets.zero,
                    initCategory: Category.RECENT,
                    bgColor: const Color(0xFFF2F2F2),
                    indicatorColor: Colors.blue,
                    iconColor: Colors.grey,
                    iconColorSelected: Colors.blue,
                    backspaceColor: Colors.blue,
                    skinToneDialogBgColor: Colors.white,
                    skinToneIndicatorColor: Colors.grey,
                    enableSkinTones: true,
                    recentTabBehavior: RecentTabBehavior.RECENT,
                    recentsLimit: 28,
                    noRecents: const Text('No Recents', style: TextStyle(fontSize: 20, color: Colors.black26),
                      textAlign: TextAlign.center,),
                    loadingIndicator: const SizedBox.shrink(),
                    tabIndicatorAnimDuration: kTabScrollDuration,
                    categoryIcons: const CategoryIcons(),
                    buttonMode: ButtonMode.MATERIAL,
                  ),
                ),
              ),
          ],
        );
      }
    );
  }
}
