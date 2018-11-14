import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  ViewController
} from "ionic-angular";
import { UtilsProvider } from "../../providers/utils/utils";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
import { AuthProvider } from "../../providers/auth/auth";

/**
 * Generated class for the ModalCountrySearchPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-modal-country-search",
  templateUrl: "modal-country-search.html"
})
export class ModalCountrySearchPage {
  items: any[];
  type: String;
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public viewCtrl: ViewController,
    private utils: UtilsProvider,
    public fb: FacebookLoginProvider,
    public auth: AuthProvider
  ) {
    this.type = this.navParams.get("type");
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  dismiss() {
    this.viewCtrl.dismiss();
  }

  selectItem(item) {
    this.viewCtrl.dismiss(item);
  }

  loadData() {
    this.utils.rest(
      `cities?per_page=50`,
      "get",
      true,
      {},
      resp => {
        this.items = resp.cities.data;
      },
      err => {}
    );
  }

  getItems(evnt: any) {
    this.utils.rest(
      `cities?per_page=50&q=${evnt.target.value}`,
      "get",
      false,
      {},
      resp => {
        this.items = resp.cities.data;
      },
      err => {}
    );
  }

  doInfinite(evn) {}

  ionViewDidLoad() {
    this.loadData();
  }
}
