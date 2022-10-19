import { defineStore } from "pinia";
import axios from 'axios'

const coins = ["USD", "BRL", 'EUR'];

export const useCoinStore = defineStore("coin", {
    state: () => ({
        _coin: "USD",
        _ratios: {}
    }),
    getters: {
        coin: (state) => state._coin,
        ratios: (state) => state._ratios,
    },
    actions: {
        async setCoin(coin) {
            if (!coins.includes(coin)) return;

            for(const c of coins) {

                if(coin == c) {
                    this._ratios[c] = 1
                    continue
                }

                const res = await axios.get(`https://economia.awesomeapi.com.br/json/last/${coin}-${c}`, {
                    transformRequest: (data, headers) => {
                        delete headers.common['X-Requested-With']
                        return data
                    },
                })

                this._ratios[c] = parseFloat(res.data[`${coin}${c}`].ask)
            }

            this._coin = coin;
        },
    },
    persist: {
        enabled: true,
    },
});
