<?php

test('contact_information returns expected value', function () {
    expect(contact_information())->toEqual(
        '<p><strong>Email:</strong> <a href="mailto:support@accessibilityexchange.ca">support@accessibilityexchange.ca</a><br />
<strong>Call or <a href="https://srvcanadavrs.ca/en/resources/resource-centre/vrs-basics/register/" rel="external">VRS</a>:</strong> 1 (888) 867-0053</p>
'
    );
});
