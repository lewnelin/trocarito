$(document).ready(function() {

    /**
     * Função para validar CNPJ usando bootstrapvalidator
     * Baseado na função para validação de CPF encontrada em:
     * https://github.com/nghuuphuoc/bootstrapvalidator/issues/187
     * usando a implementação da validação para CNPJ encontrada em:
     * http://www.fernandowobeto.com/blog/plugin-jquery-validacao-cnpj/
     *
     */
    $.fn.bootstrapValidator.validators.cnpj = {
        validate: function(validator, $field, options) {

            var r = $field.val();

            if (r == "") {
                return true
            }

            exp = /\.|\-|\//g
            r = r.toString().replace( exp, "" );
            r = r.replace(".", "");
            r = r.replace(".", "");
            r = r.replace("-", "");
            cnpj = r.replace("/", "");

            if (cnpj.length != 14) {
                return false
            }

            while (cnpj.length < 14)
                cnpj = "0" + cnpj;

            var z = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
            var s = [6,5,4,3,2,9,8,7,6,5,4,3,2];
            var o = [];
            var u = new Number;

            for (i = 0; i < 12; i++) {
                o[i] = cnpj.charAt(i);
                u += o[i] * s[i+1];
            }

            if ((x = u % 11) < 2) {
                o[12] = 0
            } else {
                o[12] = 11 - x
            }
            u = 0;

            for (y = 0; y < 13; y++)
                u += o[y] * s[y];

            if ((x = u % 11) < 2) {
                o[13] = 0
            } else {
                o[13] = 11 - x
            }

            if (cnpj.charAt(12) != o[12] || cnpj.charAt(13) != o[13] || cnpj.match(z))
                return false;
            return true
        }
    };

});