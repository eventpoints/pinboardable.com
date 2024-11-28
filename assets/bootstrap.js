import { startStimulusApp } from '@symfony/stimulus-bundle';
import TextareaAutogrow from 'stimulus-textarea-autogrow'
import ReadMore from '@stimulus-components/read-more'

const app = startStimulusApp();
app.register('textarea-autogrow', TextareaAutogrow)
app.register('read-more', ReadMore)