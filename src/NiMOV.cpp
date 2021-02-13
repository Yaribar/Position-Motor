#include "NiMOV.h"
#include "PWM_ESP32.h"

#define AIN1 12
#define AIN2 18
#define BIN1 14
#define BIN2 27
#define COAST 0
#define SHORTBRAKE 1

NiMOV::NiMOV(uint8_t pin1, uint8_t channel1, uint8_t pin2, uint8_t channel2, double frequency, uint8_t bits_resolution, float max_speed ){
    pwm1.setup(pin1, channel1, frequency, bits_resolution, true);
	pwm2.setup(pin2, channel2, frequency, bits_resolution, true);
    _max_speed=max_speed;
}
NiMOV::~NiMOV(){

}

void NiMOV::setSpeed(float raw_speed){
    if (raw_speed > 0) {
		pwm1.setDuty(0);
		pwm2.setDuty(abs(raw_speed));
	}
	else if(raw_speed<0){
		pwm1.setDuty(abs(raw_speed));
		pwm2.setDuty(0);
	}
}
void NiMOV::setDirection(boolean direction,float speed){
	if(direction){
		pwm1.setDuty(0);
		pwm2.setDuty(abs(speed));
		return;
	}
	pwm1.setDuty(abs(speed));
	pwm2.setDuty(0);
}

void NiMOV::setStop(uint8_t stop_type){
    if (stop_type == SHORTBRAKE) {
		pwm1.setDuty(100);
		pwm2.setDuty(100);
	}
	else {
		pwm1.setDuty(0);
		pwm2.setDuty(0);
	}
}