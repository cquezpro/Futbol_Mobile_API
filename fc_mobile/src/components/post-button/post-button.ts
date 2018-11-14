import { Component, Input, Output,EventEmitter } from '@angular/core';
import { PopoverController } from 'ionic-angular';
import { PostCreateComponent } from '../post-create/post-create';
/**
 * Generated class for the PostButtonComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'post-button',
  templateUrl: 'post-button.html'
})
export class PostButtonComponent {
  @Input() user_hash: any;
  @Output() post_created = new EventEmitter<any>();
  text: string;

  constructor(private popoverCtrl: PopoverController) {
  }
  open(){
  	let createPost = this.popoverCtrl.create(PostCreateComponent,
  		{user_hash:this.user_hash});
  	createPost.present({});
  	createPost.onDidDismiss(data => {
  		if(data && this.post_created){
  			this.post_created.emit(data);
  		}
    });

  }

}
