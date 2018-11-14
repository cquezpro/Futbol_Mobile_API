import { Injectable } from "@angular/core";
import { Facebook } from "@ionic-native/facebook";
import { UtilsProvider } from "../utils/utils";
import { resolve } from "url";

/*
  Generated class for the FacebookLoginProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class FacebookLoginProvider {
  constructor(private fb: Facebook, private utils: UtilsProvider) {}

  chekToken() {
    return new Promise((resolve, rejecet) => {
      this.utils.rest(
        "isl",
        "post",
        false,
        {},
        resp => {
          resolve();
        },
        err => {
          console.clear();
          if (err.error.error === "Unauthenticated.") {
            rejecet();
          }
        }
      );
    });
  }

  login(callback) {
    let t = this;
    this.fb
      .login(["public_profile", "email"]) //, 'user_gender'
      .then(profile => {
        if (profile.status === "connected") {
          let loading = t.utils.dialogLoading();
          t.fb
            .api(
              "/" +
                profile.authResponse.userID +
                "/?fields=id,email,name,picture,first_name,middle_name,last_name",
              ["public_profile"]
            ) //,gender
            .then(data => {
              let userData = {
                first_name: data.first_name + " " + data.middle_name,
                last_name: data.last_name,
                provider: "facebook",
                provider_id: data.id,
                email: data.email
                //gender:data.gender,
              };
              t.utils.rest(
                "oauth/register",
                "post",
                true,
                userData,
                data => {
                  callback(true);
                },
                undefined
              );
              loading.dismiss();
              /*
              t.auth.registerUser(userData).subscribe((response:any) =>{
                t.auth.setCurrentUser(response.data.user);
                t.auth.setCurrentToken(response.data.token);
                loading.dismiss();
                t.navCtrl.push(LandingPage);
              },(e: any) => {
                loading.dismiss();
                t.utils.toast(e.error.message);
              });*/
              t.fb
                .logout()
                .then(res => {})
                .catch(e => {
                  console.log("FBLogOutError", e);
                  callback(false);
                });
            })
            .catch(e => {
              console.log("FBDataError", e);
              callback(false);
            });
        }
      })
      .catch(e => {
        console.log("FBLoginError", e);
        callback(false);
      });
  }
}
