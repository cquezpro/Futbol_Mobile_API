import { Component, Input } from '@angular/core';

/**
 * Generated class for the PartProgressComponent component.
 *
 * See https://angular.io/api/core/Component for more info on Angular
 * Components.
 */
@Component({
  selector: 'part-progress',
  templateUrl: 'part-progress.html'
})

export class PartProgressComponent {
  @Input() title : string = '';
  @Input() icon: string;
  text: string;

  constructor() {
  }

}
