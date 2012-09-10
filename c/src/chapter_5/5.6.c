#include <stdio.h>
#include <stdbool.h>

int main(void)
{
	char size[3][12] = {
		{'6', '6', '6', '6', '7', '7', '7', '7', '7', '7', '7', '7'},
		{'1', '5', '3', '7', ' ', '1', '1', '3', '1', '5', '3', '7'},
		{'2', '8', '4', '8', ' ', '8', '4', '8', '2', '8', '4', '8'}	
	};	

	int headsize[12] = 
	{164, 166, 169, 172, 175, 178, 181, 184, 188, 191, 194, 197};

	float cranium = 0.0;
	int your_head = 0;
	int i = 0;
	bool hat_found = false;

	printf("\nEnter the circumference of your head above your eyebrows in inches as a decimal value: ");
	scanf(" %f", &cranium);

	your_head = (int)(8.0 * cranium);

	for (i = 1; i < 12; i++) {
		if (your_head > headsize[i - 1] && your_head < headsize[i]) {
			hat_found = true;
			break;	
		}	
	}

	if (your_head == headsize[0]) {
		i = 0;
		hat_found = true;	
	}

	if (hat_found) {
		printf("\n Your hat size is %c %c%c%c\n", size[0][i], size[1][i], (size[1][i] == ' ')? ' ' : '/', size[2][i]);	
	} else {
		if (your_head < headsize[0]) {
			printf("\nYour are the proverbial pinhead.No hat for you I'm afraid.\n");	
		} else {
			printf("\nYour, in technical parlance , are a fethead . No hat for you, I'm afraid.\n");	
		}
	}

	return 0;
}
