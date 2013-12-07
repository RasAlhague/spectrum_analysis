/***************************************************************
 *  SmartRF Studio(tm) Export
 *
 *  Radio register settings specifed with C-code
 *  compatible #define statements.
 *
 ***************************************************************/

#ifndef SMARTRF_CC2500_H
#define SMARTRF_CC2500_H

#define SMARTRF_RADIO_CC2500

#define SMARTRF_SETTING_FSCTRL1    0x10 //0x07 20090805, wtf does this do?
#define SMARTRF_SETTING_FSCTRL0    0x00
#define SMARTRF_SETTING_FREQ2      0x5c //0x5D	// Base freq: 2,400,000,000 / (Fxosc / 65536) = 5c4ec4
#define SMARTRF_SETTING_FREQ1      0x4e //0x93
#define SMARTRF_SETTING_FREQ0      0xde //0xB1
#define SMARTRF_SETTING_MDMCFG4    0x2d //0x2D //0e: lowest decimation (highest bandwidth). 0x2d: seems to give less noise...?
#define SMARTRF_SETTING_MDMCFG3    0x00 // 3b don't care
#define SMARTRF_SETTING_MDMCFG2    0x7e //0x73
#define SMARTRF_SETTING_MDMCFG1    0x23 //0x22	// channel spacing exponent 0x03?
#define SMARTRF_SETTING_MDMCFG0    0x9C //0xF8  // Channel spacing: Fxosc/2^18 (~99.182) * (256*mantissa) ^ exp . Desired 333KHz.
#define SMARTRF_SETTING_CHANNR     0x00 //0xEB // (256*mantissa) ^ exp = 3357469. SmartRF sez 0x43A4 for MDMCFG[1..0]. Max is 43ff (405kHz)
#define SMARTRF_SETTING_DEVIATN    0x00
#define SMARTRF_SETTING_FREND1     0xB6
#define SMARTRF_SETTING_FREND0     0x10
#define SMARTRF_SETTING_MCSM0      0x28
#define SMARTRF_SETTING_FOCCFG     0x00 // 0x1d
#define SMARTRF_SETTING_BSCFG      0x00 // 1C
#define SMARTRF_SETTING_AGCCTRL2   0xC7
#define SMARTRF_SETTING_AGCCTRL1   0x00
#define SMARTRF_SETTING_AGCCTRL0   0x06 //BC //0xB0 // 06: no automagic gain
#define SMARTRF_SETTING_FSCAL3     0xEA
#define SMARTRF_SETTING_FSCAL2     0x0A
#define SMARTRF_SETTING_FSCAL1     0x00
#define SMARTRF_SETTING_FSCAL0     0x11 //0x11
#define SMARTRF_SETTING_FSTEST     0x59
#define SMARTRF_SETTING_TEST2      0x88
#define SMARTRF_SETTING_TEST1      0x31
#define SMARTRF_SETTING_TEST0      0x0B
#define SMARTRF_SETTING_IOCFG2     0x29
#define SMARTRF_SETTING_IOCFG0D    0x06
#define SMARTRF_SETTING_PKTCTRL1   0x04
#define SMARTRF_SETTING_PKTCTRL0   0x05
#define SMARTRF_SETTING_ADDR       0x00
#define SMARTRF_SETTING_PKTLEN     0xFF

#endif

