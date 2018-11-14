import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';


import { UtilsProvider } from '../../providers/utils/utils';
/**
 * Generated class for the ValidateResendPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-validate-resend',
  templateUrl: 'validate-resend.html',
})
export class ValidateResendPage {
	private email: any;
	private phone: any;
	private countries: any = [];
	private countryCode: any = 1;
  constructor(
  	public navCtrl: NavController, 
  	public navParams: NavParams,
    public utils: UtilsProvider) {
    utils.rest('countries','get',false,null,(resp)=>{
      this.countries = resp;
    },undefined);
  }
  resend(){
    let t=this;
  	this.utils.dialogAccept('Codigo enviado','Codigo fue enviado',() =>{
      t.navCtrl.pop();
    });
  }

    goBack() {
        this.navCtrl.pop();
    }

}
