<template>
    <form class="formbox" @submit.prevent="generateStripeToken" method="POST" :action="action">
        <div :class="determineClassBasedOnErrors('name')">
            <label for="name" class="form-label">Name</label>

            <div>
                <input id="name" type="text" name="name" v-model="formData.name" required autofocus>

                <span v-if="errors.name" class="alert alert-danger">
                    {{ errors.name[0] }}
                </span>
            </div>
        </div>

        <div :class="determineClassBasedOnErrors('email')">
            <label for="email" class="form-label">E-Mail Address</label>

            <div>
                <input id="email" type="email" name="email" v-model="formData.email" required>

                <span v-if="errors.email" class="alert alert-danger">
                    {{ errors.email[0] }}
                </span>
            </div>
        </div>

        <div :class="determineClassBasedOnErrors('password')">
            <label for="password" class="form-label">Password</label>

            <div>
                <input id="password" type="password" name="password" v-model="formData.password" required>

                <span v-if="errors.password" class="alert alert-danger">
                    {{ errors.password[0] }}
                </span>
            </div>
        </div>

        <div class="mb-4">
            <label for="password-confirm" class="form-label">Confirm Password</label>

            <div>
                <input id="password-confirm" type="password" name="password_confirmation" v-model="formData.password_confirmation" required>
            </div>
        </div>

        <div :class="determineClassBasedOnErrors('country_code')">
            <label for="country_code" class="form-label">Country</label>

            <div>
                <country-list v-model="formData.country_code" name="country_code"></country-list>
            </div>

            <span v-if="errors.country_code" class="alert alert-danger">
                {{ errors.country_code[0] }}
            </span>
        </div>

        <div v-show="countryIsEUMember" :class="determineClassBasedOnErrors('vat')">
            <label for="vat" class="form-label">VAT</label>
            <span class="text-sm text-grey-dark">(optional)</span>

            <div>
                <input id="vat" type="text" name="vat" @input="checkIfValidVat" v-model="formData.vat" required>
            </div>

            <span v-if="errors.vat" class="alert alert-danger">
                {{ errors.vat[0] }}
            </span>
        </div>

        <div v-if="formData.vat" :class="determineClassBasedOnErrors('street')">
            <label for="street" class="form-label">Street</label>

            <div>
                <input id="street" type="text" name="street" v-model="formData.street" required>
            </div>

            <span v-if="errors.street" class="alert alert-danger">
                {{ errors.street[0] }}
            </span>
        </div>

        <div v-if="formData.vat" :class="determineClassBasedOnErrors('city')">
            <label for="city" class="form-label">City</label>

            <div>
                <input id="city" type="text" name="city" v-model="formData.city" required>
            </div>

            <span v-if="errors.city" class="alert alert-danger">
                {{ errors.city[0] }}
            </span>
        </div>

        <div v-if="formData.vat" :class="determineClassBasedOnErrors('zip')">
            <label for="zip" class="form-label">ZIP</label>

            <div>
                <input id="zip" type="text" name="zip" v-model="formData.zip" required>
            </div>

            <span v-if="errors.zip" class="alert alert-danger">
                {{ errors.zip[0] }}
            </span>
        </div>

        <div v-show="formData.plan !== 'none'" id="card-row" :class="cardRowClass">
            <label for="card-element" class="form-label">Credit or debit card</label>

            <div id="card-element"></div>
            <span v-show="cardError" id="card-errors" class="alert alert-danger">{{ cardError }}</span>
        </div>

        <div class="mt-6 flex items-center justify-end">
            <button type="submit" class="btn">
                {{ formData.plan !== "none" ? "Subscribe for cash money" : "Start my trial" }}
            </button>
        </div>

        <input type="hidden" name="stripe_token" v-model="chargingToken">
        <input type="hidden" name="_token" v-model="csrfToken">
    </form>
</template>

<script>
import debounce from 'debounce'
import query from 'query-string'

export default {
    props: ['plans', 'errors', 'old', 'action'],

    methods: {
        determineClassBasedOnErrors(field) {
            const baseClass = 'mb-4'
            return this.errors[field] ? `${baseClass} has-error` : baseClass
        },

        showStripeFormIfApplicable() {
            const cardIsMounted = document.getElementById('card-element').classList.contains('StripeElement')

            if (this.formData.plan === 'none') {
                this.card.unmount()
                return
            }

            if (!cardIsMounted) this.card.mount('#card-element');
        },

        generateStripeToken() {
            if (this.formData.plan === 'none') {
                this.$el.submit()
                return
            }

            this.stripe
                .createToken(this.card)
                .then(({ token, error }) => {
                    if (error) {
                        this.cardError = error.message
                        return
                    } 
                    
                    this.chargingToken = token.id
                    setTimeout(() => this.$el.submit(), 350)
                })
        },

        checkIfValidVat: debounce((e) => {
            console.log(e.target.value)
        }, 200)
    },


    computed: {
        cardRowClass() {
            const baseClass = 'mb4'
            return this.cardError ? `${baseClass} has-error` : baseClass
        },

        countryIsEUMember() {
            return this.EUMembers.indexOf(this.formData.country_code) !== -1
        }
    },

    data() {
        return {
            stripe: null,
            stripeStyles: {
                base: {},
            },
            elements: null,
            card: null,
            cardError: '',
            chargingToken: '',
            csrfToken: Configuration.csrfToken,
            EUMembers: [ 'AT', 'BE', 'HR', 'BG', 'CH', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IS', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'GB' ],
            formData: {
                name: this.old.name || '',
                email: this.old.email || '',
                vat: this.old.vat ||  '',
                country_code: this.old.country_code || '',
                password: '',
                password_confirmation: '',
                plan: this.old.plan || 'none',
            }
        }
    },

    mounted() {
        const params = query.parse(location.search)
        
        if (params.plan) {
            this.formData.plan = params.plan
        }

        this.stripe = Stripe(Configuration.stripeToken)
        this.elements = this.stripe.elements()

        this.card = this.elements.create('card', { style: this.stripeStyles, hidePostalCode: true });
        this.card.addEventListener('change', ({ error }) => this.cardError = error ? error.message : '')

        this.showStripeFormIfApplicable()
    }
 }
</script>
