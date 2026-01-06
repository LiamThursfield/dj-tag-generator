import profile from './profile'
import userPassword from './user-password'
import appearance from './appearance'
import twoFactor from './two-factor'
import apiKeys from './api-keys'

const settings = {
    profile: Object.assign(profile, profile),
    userPassword: Object.assign(userPassword, userPassword),
    appearance: Object.assign(appearance, appearance),
    twoFactor: Object.assign(twoFactor, twoFactor),
    apiKeys: Object.assign(apiKeys, apiKeys),
}

export default settings