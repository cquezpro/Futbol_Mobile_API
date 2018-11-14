import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { GlobalProvider } from "../global/global";
/*
  Generated class for the AuthProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class AuthProvider {
  headers = null;
  constructor(public http: HttpClient, public global: GlobalProvider) {
    this.headers = new HttpHeaders({
      "X-localization": "es"
    });
  }

  /*public registerUser(data){
    return this.http.post(this.global.getServerApi()+'oauth/register', data,{headers: this.headers});
  }

  public validCode(data, userId){
    return this.http.post(this.global.getServerApi()+'users/${userId}/validation', {confirmed_code: data}, {headers: this.headers});
  }

  public login(data){
    return this.http.post(this.global.getServerApi()+"oauth/login", data, {headers: this.headers});
  }
*/
  public getCurrentUser() {
    return JSON.parse(localStorage.getItem("user"));
  }
  public setCurrentUser(data) {
    localStorage.setItem("user", JSON.stringify(data));
  }
  public getCurrentToken() {
    return JSON.parse(localStorage.getItem("token"));
  }
  public setCurrentToken(data) {
    localStorage.setItem("token", JSON.stringify(data));
  }
  public logOut() {
    localStorage.removeItem("user");
    localStorage.removeItem("token");
  }

  public isLogged() {
    if (this.getCurrentUser() && this.getCurrentToken()) {
      return true;
    }
    return false;
  }
}
