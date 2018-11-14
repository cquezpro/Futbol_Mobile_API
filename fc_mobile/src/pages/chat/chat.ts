import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  Nav,
  ModalController
} from "ionic-angular";

import { UtilsProvider } from "../../providers/utils/utils";
import { AuthProvider } from "../../providers/auth/auth";
import { MessagesPage } from "../messages/messages";
import { PusherProvider } from "../../providers/pusher/pusher";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";

/**
 * Generated class for the ChatPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-chat",
  templateUrl: "chat.html"
})
export class ChatPage {
  conversations: any;
  currentUserId: any;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private utils: UtilsProvider,
    private nav: Nav,
    private auth: AuthProvider,
    public fb: FacebookLoginProvider,
    public modalCtrl: ModalController
  ) {
    this.currentUserId = this.auth.getCurrentUser().id;
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  ionViewDidLoad() {
    this.loadConversations();
  }
  loadConversations() {
    this.utils.rest(
      "conversations",
      "get",
      false,
      undefined,
      resp => {
        this.conversations = resp.conversations;
        console.log(this.conversations);
      },
      undefined
    );
  }

  newCoversation() {
    const modal = this.modalCtrl.create("ModalSearchFriendsPage");

    modal.onDidDismiss(data => {
      if (data) {
        this.nav.push("MessagesPage", {
          otherUser: data,
          conversation: null
        });
      } else {
        this.loadConversations();
      }
    });
    modal.present();
  }

  open(conversation) {
    this.nav.push("MessagesPage", {
      otherUser: null,
      conversation: conversation
    });
    //this.nav.push('MessagesPage');
  }
}
