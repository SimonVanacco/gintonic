import { startStimulusApp } from '@symfony/stimulus-bridge';
import { Autocomplete } from 'stimulus-autocomplete'

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
app.register('autocomplete', Autocomplete)