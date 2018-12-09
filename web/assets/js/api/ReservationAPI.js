import React from 'react';

export default class ReservationAPI {
    static domain() {
        return 'http://localhost:8101'
    }

    static reservationUrl(reservationId) {
        return `${this.domain()}/api/reservation/${reservationId}`
    }

    static statusUrl(reservationId) {
        return `${this.domain()}/api/reservation/${reservationId}/status`
    }
}