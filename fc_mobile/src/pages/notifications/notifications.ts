import { Component } from "@angular/core";
import { IonicPage, NavController, NavParams } from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
import { AuthProvider } from "../../providers/auth/auth";

/**
 * Generated class for the NotificationsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-notifications",
  templateUrl: "notifications.html"
})
export class NotificationsPage {
  notifications: any[] = [];
  types: any = {
    new_chat_message: "Nuevo mensaje",
    new_comment_post: "Nuevo comentario en tu post"
  };
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public fb: FacebookLoginProvider,
    public auth: AuthProvider,
    private utils: UtilsProvider
  ) {
    this.utils.rest(
      "notifications",
      "get",
      false,
      undefined,
      resp => {
        resp.notifications.forEach(element => {
          let content = "";
          let name = "";
          switch (element.type) {
            case "new_chat_message":
              name = element.data.userSender.full_name;
              content = element.message;
              break;
            case "new_comment_post":
              let comment = element.data.comment;
              name = comment.user.full_name;
              content = comment.body;
              break;
          }
          this.notifications.push({
            title: this.types[element.type],
            time: element.updated_at_diff,
            content: content,
            name: name
          });
        });
      },
      undefined
    );
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }
}
