export const switchThemeOverrides = {
    railColor: '#FFFFFF',
    railColorActive: '#FFFFFF',
    buttonColor: 'rgba(135, 41, 255, 1)'
};
export const inputThemeOverrides = {
    borderRadius: '30px',
    placeholderColor: '#EAE0FF99',
    color: '#0D143A',
    colorDisabled: '#0D143A',
    textColor: '#FFFFFF',
    colorFocus: '#0D143A',
    border: '1px solid #6522D9',
    borderHover: '1px solid #6522D9',
    borderDisabled: '1px solid #6522D9',
    borderFocus: '1px solid #6522D9',
    caretColor: 'rgba(135, 41, 255, 1)',
};

export const textareaThemeOverrides = {
    borderRadius: '5px',
    placeholderColor: '#EAE0FF99',
    color: '#0D143A',
    colorDisabled: '#0D143A',
    textColor: '#FFFFFF',
    colorFocus: '#0D143A',
    border: '1px solid #6522D9',
    borderHover: '1px solid #6522D9',
    borderDisabled: '1px solid #6522D9',
    borderFocus: '1px solid #6522D9',
    caretColor: 'rgba(135, 41, 255, 1)',
};

export const datePickerThemeOverrides = {
    peers: {
        Input: {
            textColor: '#FFFFFF',
        },
        Button: {
            textColor: '#FFFFFF',
            textColorHover: '#FFFFFF',
            textColorPressed: '#FFFFFF',
            textColorFocus: '#FFFFFF'
            // repeat for all states you need
        },
    },
    textColor: '#FFFFFF',
};

export const checkboxThemeOverrides = {
    color: '#FFFFFF',
    colorChecked: '#FFFFFF',
    checkMarkColor: 'rgba(13, 20, 58, 1)',
    borderChecked: '',
    borderFocus: ''
};

export const tableThemeOverrides = {
    tdColor: '#101741',
    thColor: '#070B28',
    thTextColor: '#EAE0FF',
    tdTextColor: '#EAE0FF'
};
export const scrollbarThemeOverrides = {
    color: '#FFFFFF',
};

export const sliderThemeOverrides = {
    railColor: '#EAE0FF99',
    railColorHover: '#EAE0FF77',
    fillColor: 'rgba(135, 41, 255, 1)',
    fillColorHover: 'rgba(135, 41, 255, 1)',
    dotColor: 'rgba(135, 41, 255, 1)',
    handleColor: 'rgba(135, 41, 255, 1)',
    dotHeight: '1px',
    dotWidth: '15px',
    dotBorderRadius: '0px',
    dotColorPopover: 'rgba(135, 41, 255, 1)',
    indicatorColor: 'rgba(135, 41, 255, 1)',
    dotColorModal: 'rgba(135, 41, 255, 1)',
    indicatorBorderRadius: '5px'
};

export const sliderGenderThemeOverrides = {
    railColor: '#dc78d8',
    fillColor: '#3259d2',
    railColorHover: '#dc78d8',
    fillColorHover: '#3259d2',
};

export const checkboxToRadioThemeOverrides = {
    color: 'transparent',
    borderRadius: '50%',
    colorChecked: 'rgba(135, 41, 255, 1)',
    checkMarkColor: 'transparent',
    border: '1px solid rgba(234, 224, 255, 1)',
    borderChecked: '1px solid rgba(234, 224, 255, 1)',
    borderFocus: '1px solid rgba(234, 224, 255, 1)'
};
export const nTabThemeOverrides = {
    colorSegment: '#8729FF',
    tabTextColorActiveSegment: '#8729FF',
    barColor: '#8729FF',
    tabTextColorBar: '#8729FF',
    tabTextColorActiveBar: '#8729FF',
    tabTextColorHoverBar: '#8729FF',
    tabTextColorLine: '#EAE0FF',
    tabTextColorActiveLine: '#8729FF',
    tabTextColorHoverLine: '#8729FF',
};
export const nTabSegmentsThemeOverrides = {
    colorSegment: 'transparent',
    tabTextColorActiveSegment: 'transparent',
    barColor: 'transparent',
    tabTextColorBar: 'transparent',
    tabTextColorActiveBar: 'transparent',
    tabTextColorHoverBar: 'transparent',
    tabTextColorLine: 'transparent',
    tabTextColorActiveLine: 'transparent',
    tabTextColorHoverLine: 'transparent',
    tabColorSegment: 'transparent',
    tabBorderRadius: '100px',
};

export const selectThemeOverrides = {
    menuBoxShadow:
        '0 6px 16px -9px rgba(0, 0, 0, .08), 0 9px 28px 0 rgba(0, 0, 0, .05), 0 12px 48px 16px rgba(0, 0, 0, .03)',
    peers: {
        InternalSelection: {
            borderRadius: '30px',
            color: 'rgba(13, 20, 58, 1)',
            colorDisabled:  'rgba(13, 20, 58, 1)',
            colorActive: 'rgba(13, 20, 58, 1)',
            border: '1px solid rgba(101, 34, 217, 1)',
            borderHover: '1px solid rgba(13, 20, 58, 1)',
            borderActive: '',
            borderFocus: '',
            boxShadowHover: '',
            boxShadowActive: '',
            boxShadowFocus: '',
            textColor: 'rgba(234, 224, 255, 1)',
            heightTiny: '42px',
            heightSmall: '42px',
            heightMedium: '42px',
            heightLarge: '42px',
        },
        InternalSelectMenu: {
            color: 'rgba(13, 20, 58, 1)',
            optionTextColor: '#EAE0FF',
            optionTextColorPressed: 'rgba(135, 41, 255, 1)',
            optionCheckColor: 'rgba(13, 20, 58, 1)',
            optionColorPending: 'rgba(13, 20, 58, 1)',
            optionColorActive: 'rgba(13, 20, 58, 1)',
            optionColorActivePending: 'rgba(13, 20, 58, 1)',
            Scrollbar: {
                color: '#EAE0FF'
            }
        },
    }
};

export const selectCatalogThemeOverrides = {
    menuBoxShadow:
        '0 6px 16px -9px rgba(0, 0, 0, .08), 0 9px 28px 0 rgba(0, 0, 0, .05), 0 12px 48px 16px rgba(0, 0, 0, .03)',
    peers: {
        InternalSelection: {
            borderRadius: '8px',
            color: 'transparent',
            colorDisabled:  'transparent',
            colorActive: 'transparent',
            border: '1px solid #6522D9',
            borderHover: '1px solid #6522D9',
            borderActive: '',
            borderFocus: '',
            boxShadowHover: '',
            boxShadowActive: '',
            boxShadowFocus: '',
            textColor: 'rgba(234, 224, 255, 1)',
            heightTiny: '25px',
            heightSmall: '25px',
            heightMedium: '25px',
            heightLarge: '25px',
        },
        InternalSelectMenu: {
            color: 'rgba(13, 20, 58, 1)',
            optionTextColor: '#EAE0FF',
            optionTextColorPressed: 'rgba(135, 41, 255, 1)',
            Scrollbar: {
                color: '#EAE0FF'
            }
        },
    }
};
