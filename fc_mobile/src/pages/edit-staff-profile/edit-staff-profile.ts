import { Component } from "@angular/core";
import { IonicPage, NavController, NavParams } from "ionic-angular";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
import { AuthProvider } from "../../providers/auth/auth";

/**
 * Generated class for the EditStaffProfilePage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-edit-staff-profile",
  templateUrl: "edit-staff-profile.html"
})
export class EditStaffProfilePage {
  constructor(
    public navCtrl: NavController,
    public navParams: NavParams,
    public fb: FacebookLoginProvider,
    public auth: AuthProvider
  ) {}

  ionViewDidLoad() {
    console.log("ionViewDidLoad EditStaffProfilePage");
  }

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }
}
