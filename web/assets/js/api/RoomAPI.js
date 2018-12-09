import React from 'react';

export default class RoomAPI {
    static domain() {
        return 'http://localhost:8101'
    }

    static roomListUrl() {
        return `${this.domain()}/api/`
    }

    static roomUrl(roomId) {
        return `${this.domain()}/api/room/${roomId}`
    }

    static bookUrl(roomId) {
        return `${this.domain()}/api/room/${roomId}/book`
    }

    static get(id) {
        return {
            'id': id,
            'some': 'data'
        }
    }

    static all() {
        return [
            {
                'id': 1,
                'some': 'data1'
            },
            {
                'id': 2,
                'some': 'data2'
            }
        ];
    }
}