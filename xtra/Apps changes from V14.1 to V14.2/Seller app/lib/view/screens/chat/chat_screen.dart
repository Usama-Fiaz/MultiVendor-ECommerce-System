import 'dart:io';

import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:provider/provider.dart';
import 'package:sixvalley_vendor_app/provider/chat_provider.dart';
import 'package:sixvalley_vendor_app/utill/color_resources.dart';
import 'package:sixvalley_vendor_app/utill/dimensions.dart';
import 'package:sixvalley_vendor_app/view/base/custom_app_bar.dart';
import 'package:sixvalley_vendor_app/view/screens/chat/widget/chat_shimmer.dart';
import 'package:sixvalley_vendor_app/view/screens/chat/widget/message_bubble.dart';
import 'package:sixvalley_vendor_app/view/screens/chat/widget/send_message_widget.dart';

class ChatScreen extends StatefulWidget {
  final String name;
  final int? userId;
  const ChatScreen({Key? key, required this.userId, this.name = ''}) : super(key: key);

  @override
  State<ChatScreen> createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> {

  final ImagePicker picker = ImagePicker();




  @override
  void initState() {
    Provider.of<ChatProvider>(context, listen: false).getMessageList(widget.userId, 1);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Consumer<ChatProvider>(builder: (context, chat, child) {
        return Column(children: [

          CustomAppBar(title: widget.name),

          Expanded(child: chat.messageList != null ? chat.messageList!.isNotEmpty ?
          ListView.builder(
            physics: const BouncingScrollPhysics(),
            padding: const EdgeInsets.all(Dimensions.paddingSizeSmall),
            itemCount: chat.messageList!.length,
            reverse: true,
            itemBuilder: (context, index) {

              return MessageBubble(message: chat.messageList![index]);
            },
          ) : const SizedBox.shrink() : const ChatShimmer()),

          chat.pickedImageFileStored != null && chat.pickedImageFileStored!.isNotEmpty ?
          Container(height: 90, width: MediaQuery.of(context).size.width,
            padding: const EdgeInsets.symmetric(horizontal: 10),
            child: ListView.builder(scrollDirection: Axis.horizontal,
              itemBuilder: (context,index){
                return  Stack(children: [
                  Padding(padding: const EdgeInsets.symmetric(horizontal: 5),
                      child: ClipRRect(borderRadius: BorderRadius.circular(10),
                          child: SizedBox(height: 80, width: 80,
                              child: Image.file(File(chat.pickedImageFileStored![index].path), fit: BoxFit.cover)))),


                  Positioned(right: 5,
                      child: InkWell(
                          child: const Icon(Icons.cancel_outlined, color: Colors.red),
                          onTap: () => chat.pickMultipleImage(true,index: index))),
                ],
                );},
              itemCount: chat.pickedImageFileStored!.length,
            ),
          ) : const SizedBox(),
          SendMessageWidget(id: widget.userId)
        ]);
      }),
    );
  }
}



