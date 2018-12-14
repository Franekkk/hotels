import React from 'react';

export default class RoomAPI {
    static domain() {
        return 'http://localhost:8101/room'
    }

    static roomListUrl() {
        return `${this.domain()}/index`
    }

    static roomUrl(roomId) {
        return `${this.domain()}/${roomId}`
    }

    static bookUrl(roomId) {
        return `${this.domain()}/book/${roomId}`
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