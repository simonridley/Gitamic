import Status from './components/Status.vue'
import Untracked from './components/Untracked.vue'
import Staged from './components/Staged.vue'

Statamic.booting(() => {
    Statamic.$components.register('gitamic-status', Status);
    Statamic.$components.register('gitamic-untracked', Untracked);
    Statamic.$components.register('gitamic-staged', Staged);
});
