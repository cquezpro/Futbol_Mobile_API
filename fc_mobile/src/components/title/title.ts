import { Component, Input,Output,EventEmitter } from '@angular/core';

/**
 * Generated class for the TitleComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'title-common',
  templateUrl: 'title.html'
})
export class TitleComponent {
  @Input() startIcon: string;
  @Input() endIcon: string;
  @Input() title: string = 'title';
  @Output() startEvent:EventEmitter<any> = new EventEmitter();
  @Output() endEvent:EventEmitter<any> = new EventEmitter();

  constructor() {}

  startClickEvent(){
  	this.startEvent.emit();
  }
  endClickEvent(){
  	this.endEvent.emit();
  }

}
