import { Component, Output,EventEmitter } from '@angular/core';
import { NavController, NavParams, ViewController } from 'ionic-angular';
/**
 * Generated class for the PopoverButtonsComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'popover-buttons',
  templateUrl: 'popover-buttons.html'
})
export class PopoverButtonsComponent {
  public buttons:any[];
  constructor(public navParams:NavParams,public viewCtrl: ViewController) {
  	this.buttons = navParams.data;
  }
  execute(event){
  	event();
  	this.viewCtrl.dismiss();
  }
}
