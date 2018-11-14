import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  ViewController
} from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";
import { AuthProvider } from "../../providers/auth/auth";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";

/**
 * Generated class for the ModalSearchFriendsPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-modal-search-friends",
  templateUrl: "modal-search-friends.html"
})
export class ModalSearchFriendsPage {
  items: any[];
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    private utils: UtilsProvider,
    private auth: AuthProvider,
    public viewCtrl: ViewController,
    public fb: FacebookLoginProvider
  ) {}

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  dismiss() {
    this.viewCtrl.dismiss();
  }

  ionViewDidLoad() {
    this.loadFriends();
  }

  loadFriends() {
    let urlFriends = `users/${this.auth.getCurrentUser().id}/get-friends`;
    this.utils.rest(
      urlFriends,
      "get",
      true,
      {},
      resp => {
        this.items = resp;
      },
      err => {}
    );
  }

  selectUser(item) {
    this.viewCtrl.dismiss(item);
  }

  //TODO: Realizar filtrado de usuarios.
  filterItems(event) {}
}
