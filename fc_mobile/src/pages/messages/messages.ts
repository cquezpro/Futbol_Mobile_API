import { Component, ViewChild } from "@angular/core";
import { IonicPage, NavController, NavParams, Content } from "ionic-angular";

import { UtilsProvider } from "../../providers/utils/utils";
import { AuthProvider } from "../../providers/auth/auth";
import { PusherProvider } from "../../providers/pusher/pusher";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
/**
 * Generated class for the MessagesPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-messages",
  templateUrl: "messages.html"
})
export class MessagesPage {
  @ViewChild(Content)
  content: Content;
  currentUser: any;
  otherUser: any;
  messages: any[] = [];
  conversation: any;
  msn: String = "";
  users: any[] = [];
  channelMessages: any;

  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private utils: UtilsProvider,
    private auth: AuthProvider,
    private fb: FacebookLoginProvider,
    private pusher: PusherProvider
  ) {
    this.currentUser = this.auth.getCurrentUser();
    this.otherUser = navParams.get("otherUser");
    this.conversation = navParams.get("conversation");

    if (this.conversation !== null) {
      console.log("Conversations: ", this.conversation);
      this.users = this.conversation.users;
      this.subscribeChanner();
      this.utils.rest(
        `conversations/${this.conversation.id}/messages`,
        "get",
        true,
        {},
        resp => {
          this.messages = resp;
          this.scrollToBottom();
        },
        undefined
      );
    } else if (this.otherUser !== null) {
      console.log("Other User: ", this.otherUser);
      this.users.push(this.otherUser);
    }
    console.clear();
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  scrollToBottom() {
    setTimeout(() => {
      this.content.scrollToBottom();
    });
  }

  send() {
    if (this.msn !== "") {
      if (this.conversation !== null) {
        this.sendMessage();
      } else {
        let url = `users/${this.otherUser.id}/conversations`;
        this.utils.rest(
          url,
          "post",
          false,
          {},
          resp => {
            this.conversation = resp;
            this.sendMessage();
          },
          undefined
        );
      }
    }
  }

  sendMessage() {
    let msn = this.msn;
    this.msn = null;
    let id = Math.random()
      .toString(36)
      .replace(/[^a-z]+/g, "")
      .substr(2, 10);

    let message = {
      id: id,
      status: "pending",
      from: {
        full_name: this.currentUser.full_name,
        avatar: this.currentUser.avatar
          ? this.currentUser.avatar.avatar_path
          : null,
        id: this.currentUser.id
      },
      body: msn,
      isRead: false,
      isSender: true
    };

    this.messages.push(message);
    this.scrollToBottom();
    let url = `conversations/${this.conversation.id}/messages`;

    this.utils.rest(
      url,
      "post",
      false,
      { body: msn },
      resp => {
        let index = this.messages.findIndex(item => item.id === id);
        if (index !== -1) {
          console.log(this.messages[index]);
          this.messages[index].status = "success";
          this.messages[index].id = resp.message.id;
        }
      },
      undefined
    );
  }

  subscribeChanner() {
    let vm = this;
    this.channelMessages = this.pusher.subscribe(
      `private-App.Conversation.${this.conversation.id}`
    );

    this.channelMessages.bind("chat.new.message", function(data) {
      let message = data.message;

      message.isSender = vm.currentUser.id == data.message.from.id;

      if (!message.isSender) vm.messages.push(message);
      let index = vm.messages.findIndex(item => item.id === message.id);
      if (index !== -1) {
        vm.messages[index].status = "success";
        console.log(vm.messages[index]);
      }

      vm.scrollToBottom();
    });
  }
}
