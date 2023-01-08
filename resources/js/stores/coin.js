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
                    const res = await axios.get(`https://economia.awesomeapi.com.br/json/last/${coin}-${c}`, {
                        transformRequest: (data, headers) => {
                            delete headers.common['X-Requested-With']
                            return data
                        },
                    })

                    console.log(res.data[`${coin}${c}`].ask);

                    this.ratios[c] = parseFloat(res.data[`${coin}${c}`].ask)
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
