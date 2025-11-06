import {createRouter, createWebHistory} from 'vue-router'

const TitleScreen = () => import('../views/TitleScreen.vue')
const GameScreen = () => import('../views/GameScreen.vue')
const ScoresScreen = () => import('../views/ScoresScreen.vue')
const SettingsScreen = () => import('../views/SettingsScreen.vue')
const PlayerSelectScreen = () => import('../views/PlayerSelectScreen.vue')

export default createRouter({
    history: createWebHistory(),
    routes: [
        {path: '/', name: 'title', component: TitleScreen},
        {path: '/player-select', name: 'player-select', component: PlayerSelectScreen},
        {path: '/game', name: 'game', component: GameScreen},
        {path: '/scores', name: 'scores', component: ScoresScreen},
        {path: '/settings', name: 'settings', component: SettingsScreen},
    ],
})
