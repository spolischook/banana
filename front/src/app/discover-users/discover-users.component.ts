import {Component, Host, OnInit} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {environment} from "../../environments/environment";
import {User, UserType} from "../Model/User";
import * as moment from 'moment';
import {NgbModal} from "@ng-bootstrap/ng-bootstrap";
import {DiscoverUsersModalComponent} from "../discover-users-modal/discover-users-modal.component";

@Component({
  selector: 'app-discover-users',
  templateUrl: './discover-users.component.html',
  styleUrls: ['./discover-users.component.scss']
})
export class DiscoverUsersComponent implements OnInit {

  public users;
  private decision;
  constructor(
      private http: HttpClient,
      private modalService: NgbModal,
  ) { }

  ngOnInit() {
    this.http.get(`${environment.apiUrl}/users`).subscribe({
        next: (users: Array<User>) => this.users = users
    });
  }

  public showItemDetails(user: User) {
      const modalRef = this.modalService.open(DiscoverUsersModalComponent);
      modalRef.componentInstance.user = user;
      modalRef.componentInstance.decision.subscribe({
          next: (user: User) => this.updateUser(user)
      })
  }

  public setAsInteresting(user: User) {
      let newUser = new User();
      newUser.pk = user.pk;
      newUser.user_type = UserType.INTERESTING_USER;

      this.updateUser(newUser);
      user.user_type = UserType.INTERESTING_USER;
  }

  public setAsNotInteresting(user: User) {
      let newUser = new User();
      newUser.pk = user.pk;
      newUser.user_type = UserType.IGNORING_USER;

      this.updateUser(newUser);
      user.user_type = UserType.IGNORING_USER;
  }

  public updateUser(user: User) {
      this.http.patch(`${environment.apiUrl}/users`, user).subscribe({
          next: () => {}
      })
  }

  fromUnixToDate(unixTimeStamp) {
      return moment.unix(unixTimeStamp).format("MM/DD/YYYY");
  }
}
