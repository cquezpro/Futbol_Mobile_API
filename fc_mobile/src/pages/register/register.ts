import { Component } from "@angular/core";
import { NavController, AlertController, IonicPage } from "ionic-angular";
import { AuthProvider } from "../../providers/auth/auth";
import { LoadingController, ToastController, Platform } from "ionic-angular";
import { LandingPage } from "../landing/landing";

import { UtilsProvider } from "../../providers/utils/utils";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";

@IonicPage()
@Component({
  selector: "page-register",
  templateUrl: "register.html"
})
export class RegisterPage {
  public event = {
    month: "1990-02-19",
    timeStarts: "07:43",
    timeEnds: "1990-02-20"
  };

  userData: object = {
    first_name: "",
    last_name: "",
    email: "",
    phone_code: null,
    password: "",
    birthday: "",
    gender: ""
  };
  /*
    
    provider:'',
    provider_id:
  */

  countrySelected: any = {
    created_at: "",
    hash_id: "",
    iso2: "",
    iso3: null,
    iso_num: null,
    name: "",
    phone_code: 0,
    updated_at: "",
    birthday: new Date()
  };

  user: any = null;

  loading: any = null;
  confirmed_code: null;
  countries;

  constructor(
    public navCtrl: NavController,
    public auth: AuthProvider,
    private utils: UtilsProvider,
    private facebook: FacebookLoginProvider
  ) {
    let t = this;
    utils.rest(
      "countries",
      "get",
      false,
      null,
      resp => {
        t.countries = resp;
      },
      undefined
    );
    if (auth.isLogged()) {
      navCtrl.push(LandingPage);
    }
  }

  saveData() {
    let t = this;
    this.utils.rest(
      "oauth/register",
      "post",
      true,
      this.userData,
      resp => {
        t.navCtrl.push("ValidateCodePage", { hash: resp.user.id });
      },
      e => {
        try {
          let message = e.error.message;
          let sizeErrors = Object.keys(e.error.errors).length;
          if (sizeErrors > 0) {
            for (var key in e.error.errors) break;
            message += `\n\n` + e.error.errors[key];
          }
          t.utils.toast(message);
        } catch (e) {
          console.log(e);
        }
      }
    );
  }

  loginFB() {
    this.facebook.login(status => {
      if (status) {
      }
    });
  }
  goBack() {
    this.navCtrl.pop();
  }
}
