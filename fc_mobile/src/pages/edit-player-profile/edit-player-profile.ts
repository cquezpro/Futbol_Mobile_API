import { Component } from "@angular/core";
import {
  IonicPage,
  NavController,
  NavParams,
  ModalController
} from "ionic-angular";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
import { AuthProvider } from "../../providers/auth/auth";

/**
 * Generated class for the EditPlayerProfilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-edit-player-profile",
  templateUrl: "edit-player-profile.html"
})
export class EditPlayerProfilePage {
  ftSelected: any[];
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public modalCtrl: ModalController,
    public fb: FacebookLoginProvider,
    public auth: AuthProvider
  ) {}

  ionViewDidLoad() {
    console.log("ionViewDidLoad EditPlayerProfilePage");
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }

  openModalFutbolTypes() {
    const modal = this.modalCtrl.create("ModalFutbolTypesPage");

    modal.onDidDismiss(data => {
      this.ftSelected = data;
    });
    modal.present();
  }
}
