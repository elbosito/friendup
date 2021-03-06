/*
 * Copyright 2019 The OpenSSL Project Authors. All Rights Reserved.
 *
 * Licensed under the Apache License 2.0 (the "License").  You may not use
 * this file except in compliance with the License.  You can obtain a copy
 * in the file LICENSE in the source distribution or at
 * https://www.openssl.org/source/license.html
 */

#include <openssl/rc4.h>
#include "internal/ciphers/ciphercommon.h"

typedef struct prov_rc4_ctx_st {
    PROV_CIPHER_CTX base;      /* Must be first */
    union {
        OSSL_UNION_ALIGN;
        RC4_KEY ks;
    } ks;
} PROV_RC4_CTX;

const PROV_CIPHER_HW *PROV_CIPHER_HW_rc4(size_t keybits);
