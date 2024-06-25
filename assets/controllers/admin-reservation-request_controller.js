// noinspection JSUnusedGlobalSymbols

import {Controller} from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ['filteredBy', 'reservations']

    reservations = this.reservationsTarget;

    initialize() {
        super.initialize();
        // this.filter('PENDING')
    }

    filter(event) {
        let reservationList = this.reservations.getElementsByClassName('reservation-result')
        console.log(event.target.value)

        for (const reservation of reservationList) {
            console.log(reservation.getAttribute('data-reservationStatus'))
            if (reservation.getAttribute('data-reservationStatus') !== event.target.value) {
                reservation.classList.add('d-none')
            } else {
                if (reservation.classList.contains('d-none')) reservation.classList.remove('d-none')
            }
        }


    }
}
