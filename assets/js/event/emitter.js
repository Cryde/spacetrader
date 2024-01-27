import mitt from 'mitt'

const emitter = mitt();

function on(eventName, callback) {
  emitter.on(eventName, callback);
}

function emit(eventName, data) {
  emitter.emit(eventName, data);
}

export {on, emit}