import React from 'react';

export default class ReservationAPI {
    static Status = {
        NEW: 0,
        ACCEPTED: 1,
        DECLINED: 2,
        CANCELED: 3,
    }

    static domain() {
        return 'http://localhost:8101/reservation'
    }

    static reservationUrl(reservationId) {
        return `${this.domain()}/${reservationId}`
    }

    static statusUrl(reservationId) {
        return `${this.domain()}/status/${reservationId}`
    }
}