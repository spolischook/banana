import {Component, EventEmitter, Input, OnInit} from '@angular/core';
import {User, UserType} from "../Model/User";
import {NgbActiveModal} from "@ng-bootstrap/ng-bootstrap";
import {ItemMediaType} from "../Model/Item";
import {DiscoverUsersComponent} from "../discover-users/discover-users.component";

@Component({
  selector: 'app-discover-users-modal',
  templateUrl: './discover-users-modal.component.html',
  styleUrls: ['./discover-users-modal.component.scss']
})
export class DiscoverUsersModalComponent implements OnInit {

  public itemMediaType = ItemMediaType;
  @Input() user: User;
  public decision = new EventEmitter<any>();
  constructor(
      public activeModal: NgbActiveModal,
  ) { }

  ngOnInit() {
  }

  public acceptUser(user: User) {
      let newUser = new User();
      newUser.pk = user.pk;
      newUser.user_type = UserType.INTERESTING_USER;
    this.decision.emit(newUser);
    this.activeModal.close();
      user.user_type = UserType.INTERESTING_USER;
  }

  public declineUser(user: User) {
      let newUser = new User();
      newUser.pk = user.pk;
      newUser.user_type = UserType.IGNORING_USER;
    this.decision.emit(newUser);
    this.activeModal.close();
    user.user_type = UserType.IGNORING_USER;
  }
}
