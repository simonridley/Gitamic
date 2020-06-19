import Status from './components/Status.vue'

Statamic.booting(() => {
    Statamic.$components.register('gitamic-status', Status);
});
