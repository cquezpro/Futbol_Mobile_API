import { Injectable } from "@angular/core";
import {
  LoadingController,
  ToastController,
  AlertController
} from "ionic-angular";
import { HttpClient, HttpHeaders } from "@angular/common/http";

import { GlobalProvider } from "../global/global";
import { AuthProvider } from "../auth/auth";
/*
  Generated class for the UtilsProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class UtilsProvider {
  constructor(
    private loadingCtrl: LoadingController,
    private toastCtrl: ToastController,
    private alertCtrl: AlertController,
    private http: HttpClient,
    private global: GlobalProvider,
    private auth: AuthProvider
  ) {}
  public dialogLoading() {
    let loading = this.loadingCtrl.create({
      content: "Por favor espere"
    });
    loading.present();
    return loading;
  }
  public toast(message: string) {
    this.toastCtrl
      .create({
        message: message,
        showCloseButton: true,
        position: "top",
        duration: 3000
      })
      .present();
  }
  public dialogTrueFalse(title, message, callback) {
    let dialog = this.alertCtrl.create({
      title: title,
      message: message,
      buttons: [
        {
          text: "No",
          handler: () => {}
        },
        {
          text: "Si",
          handler: () => {
            callback();
          }
        }
      ]
    });
    dialog.present();
  }
  public dialogAccept(title, message, callback) {
    let dialog = this.alertCtrl.create({
      title: title,
      message: message,
      buttons: [
        {
          text: "Si",
          handler: () => {
            callback();
          }
        }
      ]
    });
    dialog.present();
  }
  public getHeader() {
    let header: any = {
      "X-localization": "es"
    };
    if (this.auth.isLogged) {
      header.Authorization = "Bearer " + this.auth.getCurrentToken();
    }
    return new HttpHeaders(header);
  }
  public rest(path, type, showLoading, data, callback, error) {
    let t = this;
    let loading: any;
    if (showLoading) {
      loading = t.dialogLoading();
    }
    let rest: any;
    switch (type) {
      case "get":
        rest = t.http.get(t.global.getServerApi() + path, {
          headers: t.getHeader()
        });
        break;
      case "post":
        rest = t.http.post(t.global.getServerApi() + path, data, {
          headers: t.getHeader()
        });
        break;
      case "put":
        rest = t.http.put(t.global.getServerApi() + path, data, {
          headers: t.getHeader()
        });
        break;
      case "delete":
        rest = t.http.delete(t.global.getServerApi() + path, {
          headers: t.getHeader()
        });
        break;
      case "image":
        rest = t.http.post(t.global.getServerApi() + path, data, {
          headers: t.getHeader()
        });
        break;
      default:
        // code...
        break;
    }

    if (rest)
      rest.subscribe(
        (resp: any) => {
          callback(resp);
          if (loading) loading.dismiss();
        },
        e => {
          if (loading) loading.dismiss();
          if (error) {
            error(e);
          } else {
            t.toast(e.error.message);
          }
        }
      );
  }
  public rssRead(url, callback) {
    this.http.get(url, { responseType: "text" }).subscribe((resp: any) => {
      if (callback) {
        callback(resp);
      }
    });
  }
}
