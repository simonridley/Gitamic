import Status from './components/Status.vue'
import Unstaged from './components/Unstaged.vue'
import Staged from './components/Staged.vue'

Statamic.booting(() => {
    Statamic.$components.register('gitamic-status', Status);
    Statamic.$components.register('gitamic-unstaged', Unstaged);
    Statamic.$components.register('gitamic-staged', Staged);
});
