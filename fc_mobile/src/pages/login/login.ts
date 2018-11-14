import { Component } from "@angular/core";
import { IonicPage, NavController } from "ionic-angular";
import { AuthProvider } from "../../providers/auth/auth";
import { LandingPage } from "../landing/landing";

import { UtilsProvider } from "../../providers/utils/utils";
import { FacebookLoginProvider } from "../../providers/facebook-login/facebook-login";
/**
 * Generated class for the LoginPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: "page-login",
  templateUrl: "login.html"
})
export class LoginPage {
  private credential: any;
  constructor(
    public navCtrl: NavController,
    public auth: AuthProvider,
    public utils: UtilsProvider,
    private facebook: FacebookLoginProvider
  ) {
    this.credential = {
      emailorphone: "",
      password: ""
    };
    if (auth.isLogged()) {
      this.goLanding();
    }
  }
  goLanding() {
    this.navCtrl.push(LandingPage);
  }
  login() {
    let t = this;
    let newCredentils = {
      emailorphone: this.credential.emailorphone.toLowerCase(),
      password: this.credential.password
    };
    this.utils.rest(
      "oauth/login",
      "post",
      true,
      newCredentils,
      resp => {
        t.auth.setCurrentUser(resp.user);
        t.auth.setCurrentToken(resp.token);
        t.goLanding();
      },
      e => {
        if (e.error.data && e.error.data.isNotConfirmed) {
          t.navCtrl.push("ValidateCodePage", { hash: e.error.data.user.id });
        } else {
          t.utils.toast(e.error.message);
        }
      }
    );
  }

  loginFB() {
    let t = this;
    this.facebook.login(status => {
      if (status) {
        t.goLanding();
      }
    });
  }

  /*showPrompt(hash_id) {
    let t=this;
    const prompt = this.alertCtrl.create({
      title: 'Validar Cuenta',
      message: "Por favor ingresa el código de validacion enviado al email o teléfono ingresado",
      inputs: [
        {
          name: 'confirmed_code',
          type: 'string',
          placeholder: 'Código de validación'
        },
      ],
      buttons: [
        {
          text: 'Cancelar',
          handler: data => {}
        },
        {
          text: 'Validar',
          handler: data => {
            console.log(data.confirmed_code);
            this.auth.validCode(data.confirmed_code, hash_id)
            .subscribe(
              (resp:any) => {
                console.log(resp.data);
                let currentUser = Object.assign({}, {
                  hash_id: resp.data.user.hash_id,
                  token: resp.data.token,
                  full_name: resp.data.user.full_name
                });
                 t.auth.setCurrentUser(resp.data.user);
                 t.auth.setCurrentToken(resp.data.token);
                 t.goLanding();
              },
              (e:any) => {
                console.log(e);
              }
            );
          }
        }
      ]
    });

    prompt.present();
  }*/

  goBack() {
    this.navCtrl.pop();
  }
}
