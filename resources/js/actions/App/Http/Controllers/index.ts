import DjTagController from './DjTagController'
import VoiceController from './VoiceController'
import Settings from './Settings'

const Controllers = {
    DjTagController: Object.assign(DjTagController, DjTagController),
    VoiceController: Object.assign(VoiceController, VoiceController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers