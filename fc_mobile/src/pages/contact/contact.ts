import { Component } from "@angular/core";
import { NavController } from "ionic-angular";
import { HomePage } from "../home/home";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
import { AuthProvider } from "../../providers/auth/auth";

@Component({
  selector: "page-contact",
  templateUrl: "contact.html"
})
export class ContactPage {
  constructor(
    public navCtrl: NavController,
    public fb: FacebookLoginProvider,
    public auth: AuthProvider
  ) {}

  ionViewWillEnter() {
    this.fb.chekToken().catch(() => {
      this.auth.logOut();
      this.navCtrl.push(HomePage);
    });
  }
}
