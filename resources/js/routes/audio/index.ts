import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
export const play = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: play.url(args, options),
    method: 'get',
})

play.definition = {
    methods: ["get","head"],
    url: '/play/{version}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
play.url = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { version: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { version: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            version: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        version: typeof args.version === 'object'
        ? args.version.id
        : args.version,
    }

    return play.definition.url
            .replace('{version}', parsedArgs.version.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
play.get = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: play.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
play.head = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: play.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
const playForm = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: play.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
playForm.get = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: play.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AudioController::play
* @see app/Http/Controllers/AudioController.php:11
* @route '/play/{version}'
*/
playForm.head = (args: { version: number | { id: number } } | [version: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: play.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

play.form = playForm

const audio = {
    play: Object.assign(play, play),
}

export default audio