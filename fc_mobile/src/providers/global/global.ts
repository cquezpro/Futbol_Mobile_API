import { HttpClient } from "@angular/common/http";
import { Injectable } from "@angular/core";

/*
  Generated class for the GlobalProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class GlobalProvider {
  private serverApi;
  constructor(public http: HttpClient) {
    this.serverApi = "https://staging.futbolconnect.com/v1/";
    //this.serverApi = 'https://api.futbolconnect.org/v1/';
  }
  public getServerApi() {
    return this.serverApi;
  }
}
