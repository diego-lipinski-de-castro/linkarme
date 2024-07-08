import { defineStore } from 'pinia';
import axios from 'axios'

const coins = ['BRL', 'EUR', 'USD'];

export const useCoinStore = defineStore('coin', {
    state: () => ({
        coin: 'USD',
        ratios: {}
    }),
    actions: {
        async setCoin(coin) {
            if (!coins.includes(coin)) return;

            for(const c of coins) {

                if(coin == c) {
                    this.ratios[c] = 1
                    continue
                }

                try {
                    const res = await fetch(`https://economia.awesomeapi.com.br/json/last/${coin}-${c}`)
                    const data = await res.json()

                    this.ratios[c] = parseFloat(data[`${coin}${c}`].ask)
                } catch (error) {
                    this.ratios[c] = 0
                }
            }

            this.coin = coin;
        },
    },
    persist: {
        enabled: true,
    },
});
