import DashboardController from './DashboardController'
import DjTagController from './DjTagController'
import VoiceController from './VoiceController'
import AudioController from './AudioController'
import Settings from './Settings'

const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    DjTagController: Object.assign(DjTagController, DjTagController),
    VoiceController: Object.assign(VoiceController, VoiceController),
    AudioController: Object.assign(AudioController, AudioController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers