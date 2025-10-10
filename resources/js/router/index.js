import { createRouter, createWebHistory } from 'vue-router'

const TitleScreen = () => import('../views/TitleScreen.vue')
const GameScreen = () => import('../views/GameScreen.vue')
const ScoresScreen = () => import('../views/ScoresScreen.vue')
const SettingsScreen = () => import('../views/SettingsScreen.vue')

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'title', component: TitleScreen },
    { path: '/game', name: 'game', component: GameScreen },
    { path: '/scores', name: 'scores', component: ScoresScreen },
    { path: '/settings', name: 'settings', component: SettingsScreen },
  ],
})
